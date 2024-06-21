<?php

namespace App\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;


class MoodleGroupsModel extends Model
{
    protected $DBGroup          = 'dbmoodle';
    protected $table            = 'mdl_groups';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['courseid', 'idnumber', 'name', 'timecreated', 'timemodified'];

    public function simpan($courseid, $idnumber, $name)
    {
        $timeNow = Time::now('Asia/Jakarta', 'en_US');
        $timeStamp = $timeNow->getTimestamp();
        $dt = [
            'courseid' => $courseid,
            'idnumber' => $idnumber,
            'name' => $name,
            'timecreated' => $timeStamp,
            'timemodified' => $timeStamp
        ];

        $query = $this->insert($dt);
        if ($query) {
            return $this->getInsertID();
        } else {
            return 0;
        };
    }
}

class MoodleGroupsMembersModel extends Model
{
    protected $DBGroup          = 'dbmoodle';
    protected $table            = 'mdl_groups_members';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['groupid', 'userid', 'timeadded'];

    public function simpan($groupid, $userid)
    {
        $timeNow = Time::now('Asia/Jakarta', 'en_US');
        $timeStamp = $timeNow->getTimestamp();
        $dt = [
            'groupid' => $groupid, 'userid' => $userid, 'timeadded' => $timeStamp
        ];

        $query = $this->insert($dt);
        return $query;
    }

    public function update_kelas($group_old, $groupid, $userid)
    {
        $timeNow = Time::now('Asia/Jakarta', 'en_US');
        $timeStamp = $timeNow->getTimestamp();
        $groupmember = $this->where('groupid', $group_old)->where('userid', $userid)->first();
        $id = $groupmember['id'];
        $dt_update = [
            'groupid' => $groupid,
            'timeadded' => $timeStamp
        ];
        $query = $this->where('id', $id)->set($dt_update)->update();
        return $query;
    }
}
