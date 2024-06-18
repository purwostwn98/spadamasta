<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthUsersModel extends Model
{
    protected $table            = 'auth_users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'password', 'email_pengguna', 'sebagai', 'last_login', 'foto_profil', 'nama_pengguna'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';


    public function simpanBatch($arrayData)
    {
        $query = $this->insertBatch($arrayData);
        return $query;
    }

    public function perbaruiBatch($arrayData)
    {
        return $this->updateBatch($arrayData, 'id');
    }

    public function perbarui($id, $username, $email, $nama_pengguna)
    {
        $dt = [
            "id" => $id,
            "username" => $username,
            "email_pengguna" => $email,
            "nama_pengguna" => $nama_pengguna
        ];
        $query = $this->where('id', $id)->set($dt)->update();
        return $query;
    }

    public function simpan($username, $password, $email_pengguna, $sebagai, $nama_pengguna, $foto_profil = "user-1.jpg")
    {
        $dt = [
            "username" => $username,
            "password" => $password,
            "email_pengguna" => $email_pengguna,
            "sebagai" => $sebagai,
            "last_login" => null,
            "nama_pengguna" => $nama_pengguna,
            "foto_profil" => $foto_profil
        ];
        $q = $this->insert($dt);
        if ($q) {
            return $this->getInsertID();
        } else {
            return 0;
        }
    }
}
