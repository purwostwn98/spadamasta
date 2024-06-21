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
        } else {
            return redirect()->to('/');
        }
    }

    public function login_manual($username, $password)
    {
        $cek_user = $this->usersModel->where("username", $username)
            ->first();
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
                } elseif ($cek_user["sebagai"] == "mahasiswa") {
                    $cek_user = $this->usersModel->where("username", $username)
                        ->join('master_mahasiswa', 'auth_users.username = master_mahasiswa.nim')
                        ->first();
                    $profil_mhs = akademik_profil_mhs($uniid);
                    $idlembaga = $profil_mhs['kedelembaga'];
                    $iduser = strtolower($uniid);
                    $namauser = ucwords(strtolower($fullname));
                    $angkatan = $cek_user["angkatan"];
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
