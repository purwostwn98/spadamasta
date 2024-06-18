<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterMahasiswaModel extends Model
{
    protected $table            = 'master_mahasiswa';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nim', 'nama_mahasiswa', 'angkatan', 'idlembaga_prodi'];
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
        return $this->setData($arrayData)->onConstraint('id, nim')->updateBatch();
    }

    public function perbarui($id, $nim, $nama_mhs, $angkatan, $idlembaga)
    {
        $dt = [
            "id" => $id,
            "nim" => $nim,
            "nama_mahasiswa" => $nama_mhs,
            "angkatan" => $angkatan,
            "idlembaga_prodi" => $idlembaga
        ];
        $query = $this->where('id', $id)->set($dt)->update();
        return $query;
    }
    // public function simpan($id_cpl, $nomor_cpl, $deskripsi_cpl, $id_lembaga, $tahun_cpl, $target, $batas, $kode_sync, $is_active)
    // {
    //     $dt = [
    //         'id_cpl' => $id_cpl,
    //         'nomor_cpl' => $nomor_cpl,
    //         'deskripsi_cpl' => $deskripsi_cpl,
    //         'id_lembaga' => $id_lembaga,
    //         'tahun_cpl' => $tahun_cpl,
    //         'target' => $target,
    //         'batas' => $batas,
    //         'kode_sync' => $kode_sync,
    //         'is_active' => $is_active
    //     ];
    //     $query = $this->insert($dt);
    //     return $query;
    // }
}
