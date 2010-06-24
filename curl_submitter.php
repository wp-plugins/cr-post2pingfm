<?php

function cr_post2pingfm_do_submit_ping($api_key, $user_key, $message, $debug = false){
    $ch = curl_init();
    
    $fields = array(
        'post_method' => 'status', 
        'body' => $message,
        'user_app_key' => $user_key,
        'api_key' => $api_key);

    curl_setopt_array($ch, array(
        CURLOPT_CONNECTTIMEOUT => 2,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_USERAGENT => '[CR]Post2PingFM Submitter v.01',
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
        CURLOPT_POSTFIELDS => $fields,
        CURLOPT_URL => 'http://api.ping.fm/v1/user.post',
    ));
    
    $max_atemp = 10;
    $buf = "";
    $counter = 1;
    while(empty($buf) && $counter <= 10){
        $buf = curl_exec($ch);
        $counter++;
    }
    return $buf;
}

//$api_key = 'faec1ae02db39b8da9fd4a528e6b2006';
//$user_key = 'c4438539bf3f28c68116d6428a40c063-1221211560';
//$message = 'testing from curl';
//cr_post2pingfm_do_submit_ping($api_key, $user_key, $message);