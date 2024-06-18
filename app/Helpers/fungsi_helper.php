<?php

use App\Models\MoodleEnrolModel;
use App\Models\MoodleContextModel;

function nama_depanbelakang($string)
{
    $nama_lengkap = $string;

    $potongan_nama = explode(' ', $nama_lengkap);
    $jumlah_potongan = count($potongan_nama);

    if ($jumlah_potongan > 1) {
        $nama_belakang = $potongan_nama[$jumlah_potongan - 1];
        array_pop($potongan_nama);
        $nama_depan = implode(' ', $potongan_nama);
    } else {
        $nama_belakang = $string;
        $nama_depan = $string;
    }

    return array($nama_depan, $nama_belakang);
}

// function untuk moodle
function get_enrolid($id_course, $enrol = "manual")
{
    $mdl_enrol = new MoodleEnrolModel();
    $id = $mdl_enrol->where(['courseid' => $id_course, 'enrol' => $enrol])->first();
    return $id['id'];
}

function get_contexid($id_course, $context_level = 50)
{
    $mdl_context = new MoodleContextModel();
    $id = $mdl_context->where(['instanceid' => $id_course, 'contextlevel' => $context_level])->first();
    return $id['id'];
}
