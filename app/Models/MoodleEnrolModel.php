<?php

namespace App\Models;

use CodeIgniter\Model;

class MoodleEnrolModel extends Model
{
    protected $DBGroup          = 'dbmoodle';
    protected $table            = 'mdl_enrol';
    protected $primaryKey       = 'id';
}

class MoodleContextModel extends Model
{
    protected $DBGroup          = 'dbmoodle';
    protected $table            = 'mdl_context';
    protected $primaryKey       = 'id';
}
