<?php

function masuk_api($data_body)
{
    $session = \Config\Services::session();
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://star.ums.ac.id/abubakar/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data_body,
        CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=qiq4iji0d2hbhnfvrtgdoo9lu19kv09j'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response, true);
    return $response;
}


function token_star()
{
    return masuk_api(array('act' => 'GetToken', 'username' => 'wur115', 'password' => 'a'))['token'];
}

function get_mhs_angkatan($prodi, $angkatan)
{
    $token = token_star();
    $body = array('act' => 'ListMahasiswa', 'token' => $token, 'prodi' => $prodi, 'angkatan' => $angkatan);
    return masuk_api($body);
}

function akademik_profil_mhs($nim)
{
    $token = token_star();
    $body = array('act' => 'Mhs', 'token' => $token, 'nim' => $nim);
    return masuk_api($body);
}
