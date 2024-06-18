<?php

namespace App\Controllers;

use App\Libraries\Lib_Cas;
use App\Models\AuthUsersModel;
use App\Models\MasterMahasiswaModel;
use App\Models\TokenLoginModel;
use CodeIgniter\I18n\Time;

class Auth extends BaseController
{

    protected $usersModel;
    protected $tokenLoginModel;
    protected $masterMahasiswaModel;

    public function __construct()
    {
        $this->usersModel = new AuthUsersModel();
        $this->tokenLoginModel = new TokenLoginModel();
        $this->masterMahasiswaModel = new MasterMahasiswaModel();
    }

    public function login_ums()
    {
        $cas = new Lib_Cas;
        $cas->forceAuth();
        $user = $cas->user();
        if ($user) {
            $uniid = $user->userlogin;
            $fullname = $user->attributes['full_name'];

            if (strlen($uniid) == 10) {
                $user = $this->usersModel->where('username', strtolower($uniid))
                    ->join('master_mahasiswa', 'auth_users.username = master_mahasiswa.nim')
                    ->first();
                $mail = strtolower($uniid) . "@student.ums.ac.id";
                $profil_mhs = akademik_profil_mhs($uniid);
                if (!empty($user)) {
                    $idlembaga = $profil_mhs['kedelembaga'];
                    $iduser = strtolower($uniid);
                    $namauser = ucwords(strtolower($fullname));
                    $angkatan = $user['angkatan'];
                    $level = $user['sebagai'];
                } else {
                    $level = "mahasiswa";
                    $pswd = password_hash("mahasiswabaru@ums", PASSWORD_DEFAULT);
                    $cek_mahasiswa = $this->masterMahasiswaModel->where("nim", $uniid)->first();
                    if (empty($cek_mahasiswa)) {
                        $idlembaga = "new";
                        $angkatan = date("Y");;
                    } else {
                        $idlembaga = $cek_mahasiswa["idlembaga_prodi"];
                        $angkatan = $cek_mahasiswa["angkatan"];
                    }
                    $this->usersModel->simpan(strtolower($uniid), $pswd, $mail, $level, $fullname);
                }
            } else {
                $user = $this->usersModel->where('username', strtolower($uniid))
                    ->first();
                $idlembaga = "panitia";
                $iduser = strtolower($uniid);
                $namauser = ucwords(strtolower($fullname));
                $angkatan = 2016;
                $pswd = password_hash("panitia2024", PASSWORD_DEFAULT);
                if (!empty($user)) {
                    $mail = $user["email_pengguna"];
                    $level = $user["sebagai"];
                } else {
                    $mail = strtolower($uniid) . "@ums.ac.id";
                    $level = "panitia";
                    $this->usersModel->simpan(strtolower($uniid), $pswd, $mail, $level, $fullname);
                }
            }


            // else {
            //     if (strlen($uniid) == 10) {

            //     } else {
            //         $level = "panitia";
            //         $pswd = password_hash("panitia2024", PASSWORD_DEFAULT);
            //         $mail = $uniid . "@ums.ac.id";
            //         $angkatan = date("Y");
            //         $idlembaga = "panitia";
            //     }
            //     $this->usersModel->simpan(strtolower($uniid), $pswd, $mail, $level, $fullname);
            // }

            $data_session = [
                "login" => true,
                "iduser" => strtolower($uniid),
                "namauser" => ucwords(strtolower($fullname)),
                "level" => $level,
                "idlembaga" => $idlembaga,
                "angkatan" => $angkatan
            ];

            $_SESSION['userdata'] =  $data_session;

            $timeNow = Time::now('Asia/Jakarta', 'en_US');
            $kode_sync = $timeNow->getTimestamp();
            $token = md5($uniid . $kode_sync . 'purwostwn');
            $nama = nama_depanbelakang($fullname);
            $nama_depan = $nama[0];
            $nama_belakang = $nama[1];
            $tokenMoodle = $this->tokenLoginModel->simpan($token, strtolower($uniid), 'tanyapurwo', $nama_depan, $nama_belakang, $mail, strtolower($uniid), 0);
            if ($tokenMoodle != false) {
                $_SESSION['token_moodle'] =  $token;
            } else {
                $_SESSION['token_moodle'] =  "errorgagalsimpantoken";
            }
            if ($level == "mahasiswa") {
                return redirect()->to('/mahasiswa/dashboard');
            } elseif ($level == "panitia") {
                return redirect()->to('/panitia/dashboard');
            } elseif ($level == "superadmin") {
                return redirect()->to('/superadmin/dashboard');
            }



            // $cek_user = $this->penggunaModel->where('username', $user->userlogin)->countAllResults();
            // if ($cek_user > 0) {
            //     $_SESSION['token_kurikulum'] = "kosong";
            //     $user_row = $this->penggunaModel->where('username', $user->userlogin)->first();
            //     if (strlen($user->userlogin) == 10) {
            //         $kode_prodi = $user_row['kode_prodi'];
            //         $lembaga = $this->lembagaModel->where('kode_prodi', $kode_prodi)->first();
            //         $kode_lembaga = $lembaga['id_lembaga'];
            //         $kode_user = 10;
            //         $kaprodi = 0;
            //     } elseif ($user_row['tipe_pengguna'] == 'DOSEN') {
            //         // update prodi dosen jika belum pernah diupdate
            //         if ($user_row['kode_prodi'] == 'dosen') {
            //             $detail_dosen = get_detail_dosen($user_row['id_pengguna']);
            //             if (!empty($detail_dosen['rows'])) {
            //                 $kode_lembaga = $detail_dosen['rows']['home_id'];
            //                 $lembaga = $this->lembagaModel->where('id_lembaga', $kode_lembaga)->first();
            //                 $kode_prodi = $lembaga['kode_prodi'];
            //                 $this->penggunaModel->update_kodeprodi($user_row['id_pengguna'], $kode_prodi);
            //             } else {
            //                 $kode_prodi = 'dosen';
            //             }
            //         } else {
            //             $kode_prodi = $user_row['kode_prodi'];
            //         }

            //         // Cek apakah kaprodi atau sekprodi di tabel jabatan
            //         $find_prodi = $this->jabatanModel->where('uniid_penjabat', $user_row['id_pengguna'])->whereIn('jabatan', ['Kaprodi', 'Sekprodi'])->countAllResults();
            //         if ($find_prodi > 0) {
            //             $jabatan = $this->jabatanModel->whereIn('jabatan', ['Kaprodi', 'Sekprodi'])
            //                 ->where('uniid_penjabat', $user_row['id_pengguna'])
            //                 ->join('mstr_lembaga as lmbg', 'mstr_jabatan.kode_lembaga = lmbg.id_lembaga')->first();
            //             $kode_lembaga = $jabatan['kode_lembaga'];
            //             $kode_prodi = $jabatan['kode_prodi'];
            //             $kode_user = 8; // 8 = dosen
            //             $kaprodi = 1; // kaprodi true false
            //             $_SESSION['token_kurikulum'] =  get_token_kurikulum()['access'];
            //         } else {
            //             $delegasi = $this->delegasiModel
            //                 ->join('mstr_lembaga', 'delegasi.idlembaga = mstr_lembaga.id_lembaga')
            //                 ->where(['iddosen' => $user_row['id_pengguna'], 'jabatan' => 'kaprodi'])->first();
            //             if (!empty($delegasi)) {
            //                 //jika didelegasikan sbg kaprodi
            //                 $kode_lembaga = $delegasi['idlembaga'];
            //                 $kode_prodi = $delegasi['kode_prodi'];
            //                 $kode_user = 8;
            //                 $kaprodi = 2;
            //                 $_SESSION['token_kurikulum'] =  get_token_kurikulum()['access'];
            //             } else {
            //                 $kode_prodi = $kode_prodi;
            //                 $lembaga = $this->lembagaModel->where('kode_prodi', $kode_prodi)->first();
            //                 $kode_lembaga = $lembaga['id_lembaga'];
            //                 $kode_user = 8;
            //                 $kaprodi = 0;
            //             }
            //         }
            //     } elseif ($user_row['tipe_pengguna'] == 'SUPERADMIN') {
            //         $kode_lembaga = $user_row['kode_prodi'];
            //         $kode_prodi = 0;
            //         $kode_user = 1;
            //         $_SESSION['token_kurikulum'] =  get_token_kurikulum()['access'];
            //         $kaprodi = 0;
            //     } elseif ($user_row['tipe_pengguna'] == 'ADMIN') {
            //         $kode_lembaga = $user_row['kode_prodi'];
            //         $kode_prodi = 0;
            //         $kode_user = 2;
            //         $_SESSION['token_kurikulum'] =  get_token_kurikulum()['access'];
            //         $kaprodi = 0;
            //     }
        } else {
            // if (strlen($user->userlogin) == 10) {
            //     $mhs = get_profil_mahasiswa($user->userlogin);
            //     $kodelembaga = $mhs['kedelembaga'];
            //     $lembaga_row = $this->lembagaModel->where('id_lembaga', $kodelembaga)->first();
            //     $kode_prodi = $lembaga_row['kode_prodi'];
            //     $nim = strtolower($user->userlogin);
            //     $email = $nim . '@student.ums.ac.id';
            //     $this->penggunaModel->simpan_pengguna($nim, $nim, "", $mhs['Nama'], "MAHASISWA",  $email, $kode_prodi);
            //     $this->mhsModel->simpan_mhs($nim, $kode_prodi, $mhs['ThMasuk'], $mhs['Nama'], $email);
            // } else {
            //     $this->session->setFlashdata('errorUser', "Mahasiswa belum terdaftar, hubungi Admin SPADA");
            //     return redirect()->to('/logout')->withInput();
            // }
        }

        // cek apakah user sebagai UJM atau tidak
        // $count_delegasi_ujm = $this->delegasiModel
        //     ->where(['iddosen' => $user_row['id_pengguna'], 'jabatan' => 'ujm'])
        //     ->countAllResults();
        // $is_ujm = 0;
        // if ($count_delegasi_ujm >= 1) {
        //     $is_ujm = 1;
        // }

        // generate session
        // $data_session = [
        //     'login' => true,
        //     'kode_jabatan' => $kode_user,
        //     'kaprodi' => $kaprodi,
        //     'nama_user' => $user_row['nama_pengguna'],
        //     'id_pengguna' => $user_row['id_pengguna'],
        //     'kode_prodi' => $kode_prodi,
        //     'kode_lembaga' => $kode_lembaga,
        //     'is_ujm' => $is_ujm
        // ];

        // $_SESSION['userdata'] =  $data_session;
        // $timeNow = Time::now('Asia/Jakarta', 'en_US');
        // $kode_sync = $timeNow->getTimestamp();
        // $token = md5($user_row['id_pengguna'] . $kode_sync . 'purwostwn');
        // $nama = nama_depanbelakang($user_row['nama_pengguna']);
        // $nama_depan = $nama[0];
        // $nama_belakang = $nama[1];
        // $this->loginmoodleModel->simpan($token, $user_row['username'], 'purwostwn', $nama_depan, $nama_belakang, $user_row['email_pengguna'], $user_row['id_pengguna'], 0);
        // $_SESSION['token_moodle'] =  $token;

        // redirect
        // if ($_SESSION['userdata']['kode_jabatan'] == 1) {
        //     return redirect()->to('/home_adm');
        // } elseif ($_SESSION['userdata']['kode_jabatan'] == 2) {
        //     return redirect()->to('/admin/dashboard-fakultas');
        // } elseif ($_SESSION['userdata']['kode_jabatan'] == 8) {
        //     return redirect()->to('/dosen/home');
        // } elseif ($_SESSION['userdata']['kode_jabatan'] == 10) {
        //     return redirect()->to('/mhs/home');
        // }
    }

    public function login_manual($username, $password)
    {
        $cek_user = $this->usersModel->where("username", $username)->first();
        if (empty($cek_user)) {
            exit("Maaf username tidak valid");
        } else {
            $uniid = $cek_user["username"];
            $fullname = $cek_user["nama_pengguna"];
            if (password_verify($password, $cek_user["password"])) {
                if ($cek_user["sebagai"] == "superadmin") {
                    $idlembaga = "superadmin";
                    $iduser = strtolower($uniid);
                    $namauser = ucwords(strtolower($fullname));
                    $angkatan = 2016;
                    $level = $cek_user["sebagai"];
                } elseif ($cek_user["sebagai"] == "panitia") {
                    $idlembaga = "panitia";
                    $iduser = strtolower($uniid);
                    $namauser = ucwords(strtolower($fullname));
                    $angkatan = 2016;
                    $level = $cek_user["sebagai"];
                }

                $data_session = [
                    "login" => true,
                    "iduser" => $iduser,
                    "namauser" => $namauser,
                    "level" => $level,
                    "idlembaga" => $idlembaga,
                    "angkatan" => $angkatan
                ];

                $_SESSION['userdata'] =  $data_session;

                $timeNow = Time::now('Asia/Jakarta', 'en_US');
                $kode_sync = $timeNow->getTimestamp();
                $token = md5($cek_user['id'] . $kode_sync . 'purwostwn');
                $nama = nama_depanbelakang($cek_user['nama_pengguna']);
                $nama_depan = $nama[0];
                $nama_belakang = $nama[1];
                $tokenMoodle = $this->tokenLoginModel->simpan($token, $cek_user['username'], 'tanyapurwo', $nama_depan, $nama_belakang, $cek_user['email_pengguna'], $cek_user['username'], 0);
                if ($tokenMoodle != false) {
                    $_SESSION['token_moodle'] =  $token;
                } else {
                    $_SESSION['token_moodle'] =  "errorgagalsimpantoken";
                }
                if ($level == "superadmin") {
                    return redirect()->to('/superadmin/dashboard');
                } elseif ($level == "panitia") {
                    return redirect()->to('/panitia/dashboard');
                }
            } else {
                exit("Maaf PASSWORD tidak valid");
            }
        }
    }

    public function logout_app()
    {
        $cas = new Lib_Cas;
        $cas->keluar();
        $this->session->destroy();
        return redirect()->to('/');
    }
}
