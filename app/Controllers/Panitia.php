<?php

namespace App\Controllers;

use App\Models\AuthUsersModel;
use App\Models\MastaCourseModel;
use App\Models\MastaCourseParticipantsModel;
use App\Models\MoodleUserModel;
use App\Models\MoodleUserEnrolmentModel;
use App\Models\MoodleRoleAssignmentModel;
use CodeIgniter\I18n\Time;

class Panitia extends BaseController
{
    protected $mastaCourseModel;
    protected $mastaCourseParticipantsModel;
    protected $moodleUserEnrolmentModel;
    protected $moodleUserModel;
    protected $userAuthModel;
    protected $moodleRoleAssignmentModel;

    public function __construct()
    {
        $this->userAuthModel = new AuthUsersModel();
        $this->mastaCourseModel = new MastaCourseModel();
        $this->mastaCourseParticipantsModel = new MastaCourseParticipantsModel();
        $this->moodleUserModel = new MoodleUserModel();
        $this->moodleUserEnrolmentModel = new MoodleUserEnrolmentModel();
        $this->moodleRoleAssignmentModel = new MoodleRoleAssignmentModel();
    }

    public function dashboard(): string
    {
        $nim = $this->session->get("userdata")["iduser"];
        $namauser = $this->session->get("userdata")["namauser"];
        $participant = $this->mastaCourseParticipantsModel->where("id_peserta", strtolower($nim))->findAll();
        $arr_tahunmasta = [];
        foreach ($participant as $key => $v) {
            $arr_tahunmasta[] = $v["tahun_masta"];
        }
        if ($arr_tahunmasta != []) {
            $tgNow = new Time('now', 'Asia/Jakarta', 'en_US');
            $tgNowStamps = $tgNow->timestamp;
            $course = $this->mastaCourseModel->whereIn("tahun_masta", $arr_tahunmasta)
                ->where("tgl_selesai >=", $tgNowStamps)
                ->where("tgl_mulai <=", $tgNowStamps)
                ->findAll();
        } else {
            $course = [];
        }
        $data = [
            'title' => 'Masta UMS | ' . $namauser . ' - Dashboard',
            'sidebar' => ["", "dashboard"],
            'course' => $course
        ];
        return view('panitia/dashboard', $data);
    }
}
