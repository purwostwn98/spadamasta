<?php

namespace App\Models;

use CodeIgniter\Model;

class LembagaProdiModel extends Model
{
    protected $table            = 'master_lembaga_prodi';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['idlembaga_prodi', 'fid', 'kode_prodi', 'nama_prodi', 'idlembaga_fakultas', 'jenjang'];
}

class LembagaFakultasModel extends Model
{
    protected $table            = 'master_lembaga_fakultas';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['idlembaga_fakultas', 'nama_fakultas'];
}
