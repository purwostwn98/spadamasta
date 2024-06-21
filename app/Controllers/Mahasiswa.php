<?php

namespace App\Controllers;

use App\Models\AuthUsersModel;
use App\Models\LembagaProdiModel;
use App\Models\LembagaFakultasModel;
use App\Models\MastaCourseModel;
use App\Models\MastaCourseParticipantsModel;
use App\Models\MoodleUserModel;
use App\Models\MoodleUserEnrolmentModel;
use App\Models\MoodleRoleAssignmentModel;
use App\Models\MoodleGroupsModel;
use App\Models\MoodleGroupsMembersModel;
use CodeIgniter\I18n\Time;
use SebastianBergmann\CodeCoverage\Report\PHP;

class Mahasiswa extends BaseController
{
    protected $mastaCourseModel;
    protected $mastaCourseParticipantsModel;
    protected $moodleUserEnrolmentModel;
    protected $moodleUserModel;
    protected $userAuthModel;
    protected $moodleRoleAssignmentModel;
    protected $moodleGroupsModel;
    protected $moodleGroupsMembersModel;
    protected $lembagaProdiModel;
    protected $lembagaFakultasModel;

    public function __construct()
    {

        $this->lembagaProdiModel = new LembagaProdiModel();
        $this->lembagaFakultasModel = new LembagaFakultasModel();
        $this->userAuthModel = new AuthUsersModel();
        $this->mastaCourseModel = new MastaCourseModel();
        $this->mastaCourseParticipantsModel = new MastaCourseParticipantsModel();
        $this->moodleUserModel = new MoodleUserModel();
        $this->moodleUserEnrolmentModel = new MoodleUserEnrolmentModel();
        $this->moodleRoleAssignmentModel = new MoodleRoleAssignmentModel();
        $this->moodleGroupsModel = new MoodleGroupsModel();
        $this->moodleGroupsMembersModel = new MoodleGroupsMembersModel();
    }

    public function dashboard(): string
    {
        $nim = $this->session->get("userdata")["iduser"];
        $participant = $this->mastaCourseParticipantsModel->where("id_peserta", strtolower($nim))->findAll();
        $arr_tahunmasta = [];
        foreach ($participant as $key => $v) {
            $arr_tahunmasta[] = $v["tahun_masta"];
        }
        if ($arr_tahunmasta != []) {
            $course = $this->mastaCourseModel->whereIn("tahun_masta", $arr_tahunmasta)->findAll();
        } else {
            $course = [];
        }
        $data = [
            'title' => 'Masta UMS | Mahasiswa - Dashboard',
            'sidebar' => ["", "dashboard"],
            'course' => $course
        ];
        return view('mahasiswa/dashboard', $data);
    }

    // open ini digunakan untuk dosen, duta dan panitia juga
    public function open_masta()
    {
        $idmastacourse = $this->request->getVar("id");
        $nim = strtolower($this->session->get("userdata")["iduser"]);
        $r_mastacourse = $this->mastaCourseModel->where("id", $idmastacourse)->first();

        //siapkan data
        $id_course = $r_mastacourse['idcourse_moodle'];
        $enrolid = get_enrolid($id_course);
        $contextid = get_contexid($id_course);

        //cek apakah sudah terdaftar sebagai user moodle
        $moodleuser = $this->moodleUserModel->where("idnumber", $nim)->first();
        if (empty($moodleuser)) {
            // Tambah user moodle dulu
            $spadauser = $this->userAuthModel->where("username", $nim)->first();
            $nama = nama_depanbelakang($spadauser['nama_pengguna']);
            $nama_depan = $nama[0];
            $nama_belakang = $nama[1];

            $timeNow = Time::now('Asia/Jakarta', 'en_US');
            $timestamps = $timeNow->getTimestamp();
            $idusermoodle = $this->moodleUserModel->simpan($nim, password_hash("1!Purwostwn", PASSWORD_DEFAULT), $nama_depan, $nama_belakang, $spadauser["email_pengguna"], $nim, $timestamps);
        } else {
            $idusermoodle = $moodleuser["id"];
        }

        // cek apakah mahasiswa sudah jadi participant di moodle
        $cek_enroled = $this->moodleUserEnrolmentModel
            ->join('mdl_user', 'mdl_user_enrolments.userid = mdl_user.id')
            ->where(['enrolid' => $enrolid, 'userid' => $idusermoodle])->first();

        $p = $this->mastaCourseParticipantsModel->where(["id_peserta" => $nim, "tahun_masta" => $r_mastacourse["tahun_masta"]])->first();
        if (empty($cek_enroled)) {
            //jika belum di enrol di course
            $this->moodleUserEnrolmentModel->simpan($enrolid, $idusermoodle);
            // assign sesuai role yang ada di database spada (masta_course_participants)
            // edit join 
            $this->mastaCourseParticipantsModel->edit_joined($p["idparticipant"], 1);
            // asign di course moodle sebagai
            $this->moodleRoleAssignmentModel->simpan($contextid, $idusermoodle, $p["role"]);
        } else {
            // jika role di spada di update
            $m = $this->moodleRoleAssignmentModel->where(["mdl_role_assignments.contextid" => $contextid, "userid" => $idusermoodle])->first();
            if ($p["role"] != $m["roleid"]) {
                $this->moodleRoleAssignmentModel->update_role($m["id"], $idusermoodle, $p["role"]);
            }
        }

        // cek group fakultas mahasiswa dan duta

        if ($p["keterangan"] != "editingteacher" || $p["keterangan"] != "panitia") {
            if ($p["idlembaga_fakultas"] != null || $p["idlembaga_fakultas"] != "allfakultas") {
                //cek group di moodle
                $idNumberGroup = md5($p["idlembaga_fakultas"] . $p["tahun_masta"]);
                $group = $this->moodleGroupsModel->where(['idnumber' => $idNumberGroup, 'courseid' => $id_course])->first();
                if (!empty($group)) {
                    $idgroup = $group['id'];
                } else {
                    $f = $this->lembagaFakultasModel->where("idlembaga_fakultas", $p["idlembaga_fakultas"])->select("nama_fakultas")->first();
                    $nama_kelas = $f["nama_fakultas"];
                    $idgroup = $this->moodleGroupsModel->simpan($id_course, $idNumberGroup, $nama_kelas);
                }

                //perbarui fakultas
                $group_old = $this->moodleGroupsMembersModel->where('userid', $idusermoodle)
                    ->join('mdl_groups', 'mdl_groups_members.groupid = mdl_groups.id')
                    ->where('mdl_groups.idnumber', $idNumberGroup)
                    ->where('courseid', $id_course)->first();
                if (empty($group_old['groupid'])) {
                    // assign user ke group fakultas
                    $this->moodleGroupsMembersModel->simpan($idgroup, $idusermoodle);
                } elseif ($group_old['groupid'] != $idgroup) {
                    // update user ke group fakultas baru 
                    $this->moodleGroupsMembersModel->update_kelas($group_old['groupid'], $idgroup, $idusermoodle);
                }
            }
        }

        $tokenmasukmoodle = $this->session->get("token_moodle");
        return redirect()->to($_ENV['urlmoodle'] . '/course/view.php?id=' . $id_course . '&token=' . $tokenmasukmoodle);
    }
}
