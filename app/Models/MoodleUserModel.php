<?php

namespace App\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class MoodleUserModel extends Model
{
    protected $DBGroup          = 'dbmoodle';
    protected $table            = 'mdl_user';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'auth', 'confirmed', 'mnethostid', 'username', 'password', 'firstname', 'lastname', 'email', 'idnumber',
        'city', 'country', 'lang', 'timezone', 'timemodified'
    ];

    function simpan(
        $username,
        $password,
        $firstname,
        $lastname,
        $email,
        $idnumber,
        $timemodified
    ) {
        $dt = [
            'auth' => 'manual',
            'confirmed' => 1,
            'mnethostid' => 1,
            'username' => $username,
            'password' => $password,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'idnumber' => $idnumber,
            'city' => 'Surakarta',
            'country' => 'ID',
            'lang' => 'id',
            'timezone' => 'Asia/Jakarta',
            'timemodified' => $timemodified

        ];
        $query = $this->insert($dt);
        if ($query) {
            return $this->getInsertID();
        } else {
            return 0;
        };
    }
}

class MoodleUserEnrolmentModel extends Model
{
    protected $DBGroup          = 'dbmoodle';
    protected $table            = 'mdl_user_enrolments';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['status', 'enrolid', 'userid', 'timestart', 'timeend', 'modifierid', 'timecreated', 'timemodified'];

    public function simpan($enrolid, $userid)
    {
        $timeNow = Time::now('Asia/Jakarta', 'en_US');
        $timeStamp = $timeNow->getTimestamp();
        $dt = [
            'status' => 0,
            'enrolid' => $enrolid,
            'userid' => $userid,
            'timestart' => $timeStamp,
            'timeend' => 0,
            'modifierid' => 4,
            'timecreated' => $timeStamp,
            'timemodified' => $timeStamp
        ];
        $query = $this->insert($dt);
        return $query;
    }
}

class MoodleRoleAssignmentModel extends Model
{
    protected $DBGroup          = 'dbmoodle';
    protected $table            = 'mdl_role_assignments';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['roleid', 'contextid', 'userid', 'timemodified', 'modifierid'];

    public function simpan($contextid, $userid, $roleid = 5)
    {
        $timeNow = Time::now('Asia/Jakarta', 'en_US');
        $timeStamp = $timeNow->getTimestamp();
        $dt = [
            'roleid' => $roleid,
            'contextid' => $contextid,
            'userid' => $userid,
            'timemodified' => $timeStamp,
            'modifierid' => 4
        ];
        $query = $this->insert($dt);
        return $query;
    }

    public function update_role($id, $userid, $roleid = 5)
    {
        $timeNow = Time::now('Asia/Jakarta', 'en_US');
        $timeStamp = $timeNow->getTimestamp();
        $dt = [
            'roleid' => $roleid,
            'userid' => $userid,
            'timemodified' => $timeStamp
        ];
        $query = $this->update($id, $dt);
        return $query;
    }
}
