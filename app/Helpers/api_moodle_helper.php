<?php

function core_api($param)
{
    $token = $_ENV['tokenmoodle'];
    $urlmoodle = $_ENV['urlmoodle'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlmoodle . '/webservice/rest/server.php?moodlewsrestformat=json&wstoken=' . $token . '&' . $param,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($response, true);
    return $response;
}

function replace_spasitopersen($string)
{
    $string_hasil = str_replace(' ', '%20', $string);
    $string_hasil = str_replace('&', '%26', $string_hasil);
    return $string_hasil;
}

function time_convert($timestamp)
{
    return date('l d-m-Y H:i', $timestamp);
}

function core_course_create($fullname, $shortname)
{
    $fullname = replace_spasitopersen($fullname);
    $shortname = replace_spasitopersen($shortname);
    $param = 'wsfunction=core_course_create_courses&courses[0][fullname]=' . $fullname . '&courses[0][shortname]=' . $shortname . '&courses[0][categoryid]=1&courses[0][numsections]=3';
    return core_api($param);
}
