<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

// Auth
$routes->get('/', 'Auth::login_ums');
$routes->get('/logout', 'Auth::logout_app');
$routes->get('/auth/login/(:any)/(:any)', 'Auth::login_manual/$1/$2');


$routes->get('/superadmin/dashboard', 'Superadmin::dashboard');
$routes->get('/superadmin/course-masta', 'Superadmin::course_masta');
$routes->get('/superadmin/master-mahasiswa', 'Superadmin::master_mahasiswa');
$routes->get('/superadmin/master-mahasiwa/sinkronasi', 'Superadmin::sinkronasi_mahasiswa');

$routes->post('/superadmin/do_sinkornasi_mahasiswa', 'Superadmin::do_sinkornasi_mahasiswa');
$routes->post('/superadmin/do-create-course', 'Superadmin::do_create_course');
$routes->get('/superadmin/open-masta', 'Superadmin::open_masta');
$routes->get('/superadmin/setting-masta', 'Superadmin::v_setting_masta');
$routes->post('/superadmin/dinamis/load_setting_peserta', 'Superadmin::dinamis_load_setting_peserta');
$routes->post('/superadmin/dinamis/load_setting_panitia', 'Superadmin::dinamis_load_setting_panitia');
$routes->post('/superadmin/dinamis/load_setting_dosen', 'Superadmin::dinamis_load_setting_dosen');
$routes->post('/superadmin/unggah-peserta-excel', 'Superadmin::unggah_peserta_excel');
$routes->get('/superadmin/download_excel_peserta', 'Superadmin::download_excel_peserta');
$routes->post('/superadmin/dinamis/modal_cari_panitia', 'Superadmin::dinamis_modal_cari_panitia');
$routes->post('/superadmin/dinamis/hasil_cari_panitia', 'Superadmin::dinamis_hasil_cari_panitia');
$routes->post('/superadmin/do_jadikan_panitia', 'Superadmin::do_jadikan_panitia');
$routes->post('/superadmin/hapus-course-masta', 'Superadmin::hapus_course_masta');

// Mahasiswa
$routes->get('/mahasiswa/dashboard', 'Mahasiswa::dashboard');
$routes->get('/mahasiswa/open-masta', 'Mahasiswa::open_masta');

//Panitia
$routes->get('/panitia/dashboard', 'Panitia::dashboard');
