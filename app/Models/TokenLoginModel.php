<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenLoginModel extends Model
{
    protected $table            = 'cek_untuk_login_moodle';
    protected $primaryKey       = 'idtoken';
    protected $allowedFields    = ['token', 'username', 'password', 'firstname', 'lastname', 'email', 'idnumber', 'status'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';


    // public function simpanBatch($arrayData)
    // {
    //     $query = $this->insertBatch($arrayData);
    //     return $query;
    // }

    // public function perbaruiBatch($arrayData)
    // {
    //     return $this->setData($arrayData)->onConstraint('id, nim')->updateBatch();
    // }

    // public function perbarui($id, $nim, $nama_mhs, $angkatan, $idlembaga)
    // {
    //     $dt = [
    //         "id" => $id,
    //         "nim" => $nim,
    //         "nama_mahasiswa" => $nama_mhs,
    //         "angkatan" => $angkatan,
    //         "idlembaga_prodi" => $idlembaga
    //     ];
    //     $query = $this->where('id', $id)->set($dt)->update();
    //     return $query;
    // }

    public function simpan($token, $username, $password, $firstname, $lastname, $email, $idnumber, $status = 0)
    {
        $dt = [
            "token" => $token,
            "username" => $username,
            "password" => $password,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "idnumber" => $idnumber,
            "status" => $status
        ];
        $this->transBegin();
        $this->insert($dt);
        if ($this->transStatus() === false) {
            $this->transRollback();
            $msg = false;
        } else {
            $this->transCommit();
            $msg = $token;
        }
        return $msg;
    }
}
