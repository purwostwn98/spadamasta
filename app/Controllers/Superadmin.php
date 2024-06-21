<?php

namespace App\Controllers;

use App\Models\AuthUsersModel;
use App\Models\LembagaProdiModel;
use App\Models\LembagaFakultasModel;
use App\Models\MastaCourseModel;
use App\Models\MastaCourseParticipantsModel;
use App\Models\MasterMahasiswaModel;
use App\Models\MoodleUserModel;
use App\Models\MoodleRoleAssignmentModel;
use App\Models\MoodleUserEnrolmentModel;
use CodeIgniter\I18n\Time;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Superadmin extends BaseController
{
    //protected variables
    protected $lembagaProdiModel;
    protected $lembagaFakultasModel;
    protected $masterMahasiswaModel;
    protected $authUsersModel;
    protected $mastaCourseModel;
    protected $mastaCourseParticipantsModel;
    protected $moodleUserModel;
    protected $moodleUserEnrolmentModel;
    protected $moodleRoleAssignmentModel;

    public function __construct()
    {
        // call model
        $this->lembagaProdiModel = new LembagaProdiModel();
        $this->lembagaFakultasModel = new LembagaFakultasModel();
        $this->masterMahasiswaModel = new MasterMahasiswaModel();
        $this->authUsersModel = new AuthUsersModel();
        $this->mastaCourseModel = new MastaCourseModel();
        $this->mastaCourseParticipantsModel = new MastaCourseParticipantsModel();
        $this->moodleUserModel = new MoodleUserModel();
        $this->moodleUserEnrolmentModel = new MoodleUserEnrolmentModel();
        $this->moodleRoleAssignmentModel = new MoodleRoleAssignmentModel();
    }

    public function dashboard(): string
    {
        $data = [
            'title' => 'Masta UMS | Superadmin - Dashboard',
            'sidebar' => ["", "dashboard"]
        ];
        return view('superadmin/dashboard', $data);
    }

    public function course_masta(): string
    {
        $data = $this->mastaCourseModel->orderBy("tahun_masta", "desc")->findAll(10, 0);
        $arr_tampil = [];
        foreach ($data as $key => $v) {
            $jml_peserta = $this->mastaCourseParticipantsModel->where(["tahun_masta" => $v["tahun_masta"], "role" => 5])->countAllResults();
            $jml_joined = $this->mastaCourseParticipantsModel->where(["tahun_masta" => $v["tahun_masta"], "role" => 5, "join_course" => 1])->countAllResults();
            $arr_tampil[] = array(
                "id" => $v['id'],
                "nama" => $v['judul_masta'],
                "tahun" => $v['tahun_masta'],
                "semuapeserta" => $jml_peserta,
                "joinedpeserta" => $jml_joined,
                "mulai" => time_convert($v["tgl_mulai"])
            );
        }

        $data = [
            'title' => 'Masta UMS | Superadmin - Course Masta',
            'sidebar' => ["", "course-masta"],
            'course' => $arr_tampil
        ];
        return view('superadmin/course_masta', $data);
    }

    public function master_mahasiswa(): string
    {
        $data = [
            'title' => 'Masta UMS | Master Mahasiswa',
            'sidebar' => ["", "master-mahasiswa"]
        ];
        return view('superadmin/master_mahasiswa', $data);
    }

    public function sinkronasi_mahasiswa(): string
    {
        $lembaga_prodi = $this->lembagaProdiModel->orderBy('idlembaga_fakultas, id')->findAll();
        $data = [
            'title' => 'Masta UMS | Master Mahasiswa',
            'lembaga' => $lembaga_prodi,
            'sidebar' => ["", "master-mahasiswa"]
        ];
        return view('superadmin/sinkronasi_mahasiswa', $data);
    }

    public function do_sinkornasi_mahasiswa()
    {
        if ($this->request->isAJAX()) {
            $angkatan = $this->request->getPost('angkatan');
            $prodi = $this->request->getPost('prodi');
            $lembaga = $this->lembagaProdiModel->where('kode_prodi', $prodi)->first();
            $idlembaga = $lembaga['idlembaga_prodi'];
            $mahasiswa_angkatan = get_mhs_angkatan($prodi, $angkatan);
            if (!$mahasiswa_angkatan) {
                $mahasiswa_angkatan = [];
            }

            $data_tersimpan = $this->masterMahasiswaModel->where('idlembaga_prodi', $idlembaga)->where('angkatan', $angkatan)->findAll();
            $mhs = [];
            foreach ($data_tersimpan as $i => $val) {
                $nim = strtolower($val['nim']);
                $mhs[$nim] = $val;
            }

            $jml_simpan = 0;
            $jml_update = 0;
            $arrUpdateMhs = [];
            $arrUpdateUser = [];
            $arrSimpanMhs = [];
            $arrSimpanUser = [];
            if ($mahasiswa_angkatan['success'] == 'true') {
                foreach ($mahasiswa_angkatan['rows'] as $key => $val) {
                    $nim = strtolower($val['nim']);
                    // Untuk cek apakah mahasiswa sudah didlm tabel spada?
                    if (array_key_exists($nim, $mhs)) {
                        if ($val["nama"] != $mhs[$nim]['nama_mahasiswa']) {
                            $this->masterMahasiswaModel->perbarui($mhs[$nim]['id'], $nim, $val['nama'], $val['angkatan'], $idlembaga);
                            $user = $this->authUsersModel->where('username', $nim)->first();
                            $this->authUsersModel->perbarui($user['id'], $nim, $val['email'], $val["nama"]);
                            // $arrUpdateUser[$nim] = [
                            //     "id" => $user['id'],
                            //     "username" => $nim,
                            //     "password" => password_hash("mahasiswabaru@ums", PASSWORD_DEFAULT),
                            //     "email_pengguna" => $val['email'],
                            //     "sebagai" => "mahasiswa",
                            //     "nama_pengguna" => $val["nama"],
                            //     "last_login" => $user['last_login'],
                            //     "foto_profil" => $user['foto_profil']
                            // ];
                            $jml_update++;
                        }
                    } else {
                        $arrSimpanMhs[$nim] = [
                            "nim" => $nim,
                            "nama_mahasiswa" => $val["nama"],
                            "angkatan" => $val["angkatan"],
                            "idlembaga_prodi" => $idlembaga
                        ];
                        $arrSimpanUser[$nim] = [
                            "username" => $nim,
                            "password" => password_hash("mahasiswabaru@ums", PASSWORD_DEFAULT),
                            "email_pengguna" => $val['email'],
                            "sebagai" => "mahasiswa",
                            "foto_profil" => "user-1.jpg",
                            "nama_pengguna" => $val["nama"]
                        ];
                        $jml_simpan++;
                    }
                }
            } else {
                $jml_simpan = 0;
            }

            if ($jml_simpan > 0) {
                $this->masterMahasiswaModel->simpanBatch($arrSimpanMhs);
                $this->authUsersModel->simpanBatch($arrSimpanUser);
            }

            $msg = [
                'berhasil' => [
                    'jumlah_simpan' => $jml_simpan,
                    'jumlah_update' => $jml_update,
                    'kode_prodi' => $prodi,
                    'angkatan' => $angkatan,
                    'nama_prodi' => $lembaga['nama_prodi']
                ],
                'token' => csrf_hash()
            ];
            echo json_encode($msg);
        } else {
            exit('Mohon maaf, tidak dapat diproses');
        }
    }

    public function do_create_course()
    {
        if (isset($_POST['submitMasta'])) {
            $judul_masta = $this->request->getPost('judul_masta');
            $tahun_masta = $this->request->getPost('tahun_masta');
            $tgl_mulai = $this->request->getPost('tgl_mulai');
            $tgl_selesai = $this->request->getPost('tgl_selesai');
            $tanggal_mulai = new DateTime(strval($tgl_mulai));
            $tanggal_selesai = new DateTime(strval($tgl_selesai));
            $timenow = Time::now('Asia/Jakarta');
            $tgl_mulai_stamps = $tanggal_mulai->getTimestamp();
            $tgl_selesai_stamps = $tanggal_selesai->getTimestamp();
            $tgl_now_stamps = $timenow->getTimestamp();

            //simpan ke api moodle
            $create = core_course_create($judul_masta, "MASTA-" . $tahun_masta);
            if (!empty($create[0]['id'])) {
                $idcourse = $create[0]['id'];
                $simpan = $this->mastaCourseModel->simpan($judul_masta, $tahun_masta, $tgl_mulai_stamps, $tgl_selesai_stamps, $idcourse, $tgl_now_stamps);
                if ($simpan != false) {
                    $this->session->setFlashdata('berhasil', "Course masta " . $tahun_masta . " berhasil disimpan");
                } else {
                    $this->session->setFlashdata('gagal', "Course masta gagal disimpan");
                }
                // tambahkan user superadmin ke participant sebagai manager
                $this->mastaCourseParticipantsModel->simpan($this->session->get("userdata")["iduser"], 1, $tahun_masta, 1);
            } else {
                $this->session->setFlashdata('gagal', "Course masta gagal disimpan");
            }
            return redirect()->to('/superadmin/course-masta')->withInput();
        }
    }

    public function open_masta()
    {
        $idmastacourse = $this->request->getVar("id");
        $nim = strtolower($this->session->get("userdata")["iduser"]);
        $r_mastacourse = $this->mastaCourseModel->where("id", $idmastacourse)->first();

        //siapkan data
        $id_course = $r_mastacourse['idcourse_moodle'];

        $tokenmasukmoodle = $this->session->get("token_moodle");
        return redirect()->to($_ENV['urlmoodle'] . '/course/view.php?id=' . $id_course . '&token=' . $tokenmasukmoodle);
    }

    public function v_setting_masta()
    {
        $id = $this->request->getVar("id");
        $course = $this->mastaCourseModel->where("id", $id)->first();
        $tgl_mulai = time_convert($course["tgl_mulai"]);
        $tgl_selesai = time_convert($course["tgl_selesai"]);
        $data = [
            'title' => 'Masta UMS | Superadmin - Setting Masta',
            'sidebar' => ["", "course-masta"],
            'course' => $course,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai
        ];
        return view('superadmin/setting_masta', $data);
    }

    public function dinamis_load_setting_peserta()
    {
        $id = $this->request->getVar("id");
        $idlembaga = $this->request->getVar("idlembaga");
        $mastaCourse = $this->mastaCourseModel->where("id", $id)->first();
        $prodi = $this->lembagaProdiModel->findAll();
        $peserta = $this->mastaCourseParticipantsModel
            ->where(["tahun_masta" => $mastaCourse["tahun_masta"], "role" => 5])
            ->join("master_mahasiswa as mhs", "masta_course_participants.id_peserta = mhs.nim")
            ->join("master_lembaga_prodi as prodi", "mhs.idlembaga_prodi = prodi.idlembaga_prodi")
            ->where("mhs.idlembaga_prodi", $idlembaga)
            ->select("idparticipant, nim, nama_mahasiswa, nama_prodi, angkatan")
            ->orderBy("nim")
            ->findAll();
        $datatampil = [
            "peserta" => $peserta,
            "prodi" => $prodi,
            "idlembaga" => $idlembaga,
            "tahun_masta" => $mastaCourse["tahun_masta"],
            "idmasta" => $id
        ];
        $data = [
            'tabel' => view("superadmin/dinamis/tabel_peserta", $datatampil)
        ];
        echo json_encode($data);
    }

    public function dinamis_load_setting_panitia()
    {
        $id = $this->request->getVar("id");
        $keterangan = $this->request->getVar("keterangan");

        $mastaCourse = $this->mastaCourseModel->where("id", $id)->first();
        if ($keterangan == "duta") {
            $peserta = $this->mastaCourseParticipantsModel
                ->where(["tahun_masta" => $mastaCourse["tahun_masta"], "role" => 4])
                ->where("keterangan", $keterangan)
                ->join("auth_users as user", "masta_course_participants.id_peserta = user.username")
                ->join("master_lembaga_fakultas as f", "masta_course_participants.idlembaga_fakultas = f.idlembaga_fakultas")
                ->select("idparticipant, username, nama_pengguna, nama_fakultas")
                ->orderBy("nama_pengguna")
                ->findAll();
        } else {
            $peserta = $this->mastaCourseParticipantsModel
                ->where(["tahun_masta" => $mastaCourse["tahun_masta"], "role" => 4])
                ->where("keterangan", $keterangan)
                ->join("auth_users as user", "masta_course_participants.id_peserta = user.username")
                ->select("idparticipant, username, nama_pengguna")
                ->orderBy("nama_pengguna")
                ->findAll();
        }

        $datatampil = [
            "peserta" => $peserta,
            "keterangan" => $keterangan
        ];
        $data = [
            'tabel' => view("superadmin/dinamis/tabel_panitia", $datatampil)
        ];
        echo json_encode($data);
    }

    public function dinamis_load_setting_dosen()
    {
        $id = $this->request->getVar("id");
        $mastaCourse = $this->mastaCourseModel->where("id", $id)->first();
        $peserta = $this->mastaCourseParticipantsModel
            ->where(["tahun_masta" => $mastaCourse["tahun_masta"], "role" => 3])
            ->join("auth_users as user", "masta_course_participants.id_peserta = user.username")
            ->select("idparticipant, username, nama_pengguna")
            ->orderBy("nama_pengguna")
            ->findAll();
        $datatampil = [
            "peserta" => $peserta
        ];
        $data = [
            'tabel' => view("superadmin/dinamis/tabel_dosen", $datatampil)
        ];
        echo json_encode($data);
    }

    public function download_excel_peserta()
    {
        ini_set('memory_limit', '512M');
        $file_excel = $_SERVER["DOCUMENT_ROOT"] . '/excel/' . 'template_peserta_masta.xlsx';
        $reader = IOFactory::createReader('Xlsx');
        $reader->setIncludeCharts(true);
        $spreadsheet = $reader->load($file_excel);

        // get variable
        $idlembaga = $this->request->getVar("idlembaga");
        $tahun_masta = $this->request->getVar("tahunmasta");
        $idmasta = $this->request->getVar("idmasta");
        $prodi = $this->lembagaProdiModel->where("idlembaga_prodi", $idlembaga)->first();
        $mastacourse = $this->mastaCourseModel->where("id", $idmasta)->first();

        // judul sheet info
        $spreadsheet->setActiveSheetIndexByName("peserta")
            ->setCellValue('A7', $tahun_masta)
            ->setCellValue('A8', $idlembaga)
            ->setCellValue('B5', "Nama masta : " . $mastacourse["judul_masta"])
            ->setCellValue('B6', "Tahun masta : " . $tahun_masta)
            ->setCellValue('D6', "Program Studi : " . $prodi["nama_prodi"]);;

        $nama_file = 'Peserta Masta - ' . $mastacourse["judul_masta"] . " " . $prodi["nama_prodi"];
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nama_file . '.xlsx"');
        ob_end_clean();
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        $writer->save('php://output');
        exit();
    }

    public function unggah_peserta_excel()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();
            $valid = [
                'file_excel' => [
                    'label' => 'File Excel',
                    'rules' => 'uploaded[file_excel]|max_size[file_excel,4096]|ext_in[file_excel,xlsx]',
                    'errors' => [
                        'uploaded' => '{field} tidak boleh kosong',
                        'max_size' => 'Mohon maaf, ukuran {field} tidak boleh melebihi 4MB',
                        'ext_in' => 'Mohon maaf, {field} harus dalam format .xlsx',
                        'mime_in' => 'Mohon maaf, {field} bukan .xlsx',
                    ]
                ],
            ];
            if (!$this->validate($valid)) {
                $msg = [
                    'success' => false,
                    'pesan' => $validation->getError('file_excel'),
                    'token' => csrf_hash()
                ];
            } else {
                $file_excel = $this->request->getFile('file_excel');
                $ext = $file_excel->getClientExtension();
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet = $reader->load($file_excel);

                // $idmk = $this->request->getPost('idmk');
                // $periode = $this->request->getPost('periode');
                // validasi di dalam file excelnya (mencocokan kode)
                if (!empty($spreadsheet->getSheetByName("peserta"))) {
                    $tahun_masta = $spreadsheet->setActiveSheetIndexByName("peserta")->getCell('A7')->getValue();
                    $idlembaga = $spreadsheet->setActiveSheetIndexByName("peserta")->getCell('A8')->getValue();
                    $rlembaga = $this->lembagaProdiModel->where("idlembaga_prodi", $idlembaga)->first();
                    $idfakultas = $rlembaga["idlembaga_fakultas"];

                    $data = $spreadsheet->setActiveSheetIndexByName("peserta")->toArray();
                    $arr_nim = [];
                    $arr_mhs = [];
                    $arr_peserta = [];
                    $jmlsimpan = 0;
                    foreach ($data as $row => $col) {

                        if ($row < 9) {
                            continue;
                        } else {

                            if ($col[2] == "" || empty($col[2])) {
                                break;
                            }

                            $nim = strtolower($col[2]);
                            $nama = $col[3];
                            $angkatan = $col[4];

                            $arr_nim[] = $nim;

                            $arr_mhs[$nim] = [
                                "nim" => $nim,
                                "nama_mahasiswa" => $nama,
                                "angkatan" => $angkatan,
                                "idlembaga_prodi" => $idlembaga
                            ];

                            $arr_peserta[$nim] = [
                                "id_peserta" => $nim,
                                "role" => 5,
                                "tahun_masta" => $tahun_masta,
                                "join_course" => 0,
                                "keterangan" => "student",
                                "idlembaga_fakultas" => $idfakultas
                            ];
                        }
                    }

                    $mhs_tersimpan = $this->masterMahasiswaModel->whereIn("nim", $arr_nim)->select("nim, nama_mahasiswa, id")->findAll();
                    foreach ($mhs_tersimpan as $m => $mhs) {
                        // Hapus mahasiswa tersimpan dari arr simpan mhs
                        if (array_key_exists($mhs["nim"], $arr_mhs)) {
                            if ($mhs["nama_mahasiswa"] != $arr_mhs[$mhs["nim"]]["nama_mahasiswa"]) {
                                //perbarui nama
                                $this->masterMahasiswaModel->perbarui($mhs["id"], $mhs["nim"], $arr_mhs[$mhs["nim"]]["nama_mahasiswa"], $arr_mhs[$mhs["nim"]]["angkatan"], $idlembaga);
                            }
                            unset($arr_mhs[$mhs["nim"]]);
                        }
                    }

                    $peserta_tersimpan = $this->mastaCourseParticipantsModel->whereIn("id_peserta", $arr_nim)
                        ->where("tahun_masta", $tahun_masta)
                        ->select("id_peserta, tahun_masta, idparticipant, idlembaga_fakultas")->findAll();
                    foreach ($peserta_tersimpan as $p => $psta) {
                        if (array_key_exists($psta["id_peserta"], $arr_peserta)) {
                            if ($psta["idlembaga_fakultas"] != $idfakultas) {
                                //update fakultas
                                $this->mastaCourseParticipantsModel->update_participant_role($psta["idparticipant"], 5, "student", $idfakultas);
                            }
                            //hapus dari array tambah supaya tidak ikut ditambah batch
                            unset($arr_peserta[$psta["id_peserta"]]);
                        }
                    }

                    // baru simpan batch
                    if (!empty($arr_mhs)) {
                        $this->masterMahasiswaModel->simpanBatch($arr_mhs);
                    }

                    if (!empty($arr_peserta)) {
                        $this->mastaCourseParticipantsModel->simpanBatch($arr_peserta);
                        $jmlsimpan = count($arr_peserta);
                    }

                    $msg = [
                        'success' => true,
                        'jmlsimpan' => $jmlsimpan,
                        'pesan' => "Peserta berhasil ditambah",
                        'token' => csrf_hash()
                    ];
                } else {
                    $msg = [
                        'success' => false,
                        'pesan' => "File excel tidak valid! Pastikan Anda mendownload dari halaman ini",
                        'token' => csrf_hash()
                    ];
                }
            }
            echo json_encode($msg);
        } else {
            echo ("Maaf perintah anda tidak dapat diproses");
        }
    }

    public function dinamis_modal_cari_panitia()
    {
        $role = $this->request->getVar("role");
        $keterangan = $this->request->getVar("keterangan");
        $fakultas = $this->lembagaFakultasModel->findAll();
        $datatampil = [
            "role" => $role,
            "keterangan" => $keterangan,
            "fakultas" => $fakultas
        ];
        $data = [
            'modal' => view("superadmin/dinamis/modal_tambah_panitia", $datatampil)
        ];
        echo json_encode($data);
    }

    //yang termasuk panitia pada fungsi ini (dosen, panitia, dutamasta)
    public function dinamis_hasil_cari_panitia()
    {
        $key = $this->request->getPost("key");
        $role = $this->request->getPost("role");
        $keterangan = $this->request->getPost("keterangan");
        $fakultas = $this->request->getPost("fakultas");
        $users = $this->authUsersModel->orderBy('username', 'ASC')
            ->like('LOWER(username)', strtolower(strval($key)))->orLike('LOWER(nama_pengguna)', strtolower(strval($key)))
            ->findAll();

        $tr = "";
        foreach ($users as $key => $v) {
            $tr .= '<tr><td>' . $key + 1 . '</td>
            <td>' . $v["username"] . '</td>
            <td>' . $v["nama_pengguna"] . '</td>
            <td class="text-center"><button class="btn btn-primary btn-sm" value="' . $v["username"] . '-' . $role . '-' . $keterangan . '-' . $fakultas . '" onclick="makePanitia(this.value)"><i class="fa fa-user-plus"></i></button></td>
            </tr>';
        }
        $datakirim = [
            'tr' => $tr
        ];
        echo json_encode($datakirim);
    }

    public function do_jadikan_panitia()
    {
        $params = $this->request->getVar("params");
        $ec = explode("-", $params);
        $iduser = $ec[0];
        $role = $ec[1];
        $keterangan = $ec[2];
        $fakultas = $ec[3];
        $idmasta = $this->request->getVar("idmasta");
        $mastacourse = $this->mastaCourseModel->where("id", $idmasta)->first();
        $tahun_masta = $mastacourse["tahun_masta"];
        // simpan
        $cek = $this->mastaCourseParticipantsModel->where(["id_peserta" => $iduser, "tahun_masta" => $tahun_masta])->first();
        if ($cek >= 1) {
            if ($role != $cek["role"] || $keterangan != $cek["keterangan"] || $fakultas != $cek["idlembaga_fakultas"]) {
                $query = $this->mastaCourseParticipantsModel->update_participant_role($cek["idparticipant"], $role, $keterangan, $fakultas);
            }
        } else {
            $query = $this->mastaCourseParticipantsModel->simpan($iduser, $role, $tahun_masta, $keterangan, $fakultas, 0);
        }
        if ($query != 0) {
            $msg = [
                "status" => true,
                "token" => csrf_hash(),
                "pesan" => ($role == 4 ? ucwords($keterangan) : "Dosen") . " berhasil ditambahkan",
                "role" => $role,
                "keterangan" => $keterangan
            ];
        } else {
            $msg = [
                "status" => false,
                "token" => csrf_hash(),
                "pesan" => ($role == 4 ? "Panitia" : "Dosen") . " gagal ditambahkan",
                "role" => $role,
                "keterangan" => $keterangan
            ];
        }
        echo json_encode($msg);
    }
}
