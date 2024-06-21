<?php

namespace App\Models;

use CodeIgniter\Model;

class MastaCourseModel extends Model
{
    protected $table            = 'masta_course';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['judul_masta', 'tahun_masta', 'tgl_mulai', 'tgl_selesai', 'idcourse_moodle', 'last_sync', 'created_by'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function simpan($judul_masta, $tahun_masta, $tgl_mulai, $tgl_selesai, $idcourse_moodle, $last_sync, $created_by = "superadmin")
    {
        $data = [
            'judul_masta' => $judul_masta,
            'tahun_masta' => $tahun_masta,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'idcourse_moodle' => $idcourse_moodle,
            'last_sync' => $last_sync,
            'created_by' => $created_by
        ];

        $this->insert($data);

        return $this->getInsertID();
    }
}

class MastaCourseParticipantsModel extends Model
{
    protected $table            = 'masta_course_participants';
    protected $primaryKey       = 'idparticipant';
    protected $allowedFields    = ['id_peserta', 'role', 'tahun_masta', 'join_course', 'keterangan', 'idlembaga_fakultas'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function edit_joined($id, $join = 1)
    {
        $dt = [
            "join_course" => $join
        ];
        $query = $this->update($id, $dt);
        return $query;
    }

    public function simpan($id_peserta, $role, $tahun_masta, $keterangan, $idlembaga_fakultas = null, $join_course = 0)
    {
        $dt = [
            "id_peserta" => $id_peserta,
            "role" => $role,
            "tahun_masta" => $tahun_masta,
            "join_course" => $join_course,
            "keterangan" => $keterangan,
            "idlembaga_fakultas" => $idlembaga_fakultas
        ];
        $query = $this->insert($dt);
        if ($query) {
            return $this->getInsertID();
        } else {
            return 0;
        }
    }

    public function update_participant_role($id, $role, $keterangan, $idlembaga_fakultas)
    {
        $dt = [
            "role" => $role,
            "keterangan" => $keterangan,
            "idlembaga_fakultas" => $idlembaga_fakultas
        ];
        $query = $this->update($id, $dt);
        if ($query) {
            return $id;
        } else {
            return 0;
        }
    }

    public function edit_tahunmasta($id, $tahun_masta)
    {
        $dt = [
            "tahun_masta" => $tahun_masta
        ];
        $query = $this->update($id, $dt);
        return $query;
    }

    public function simpanBatch($arrayData)
    {
        $query = $this->insertBatch($arrayData);
        return $query;
    }
}
