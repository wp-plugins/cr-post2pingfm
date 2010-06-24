<?php

function cr_post2pingfm_do_submit_ping($api_key, $user_key, $message, $debug = false){
    $postdata = "post_method=status&body={$message}&user_app_key={$user_key}&api_key={$api_key}";
    //echo "\$postdata: $postdata\n";
    
    $max_atemp = 10;
    $buf = "";
    $counter = 1;
    while(empty($buf) && $counter <= 10){
        //echo "Attemp #{$counter}<br />\n";
        $fp = fsockopen("api.ping.fm", 80, $errno, $errstr, 30);
        fputs($fp, "POST /v1/user.post HTTP/1.0\r\n");
        fputs($fp, "Host: api.ping.fm\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: " . strlen($postdata) . "\r\n");
        fputs($fp, "User-agent: [CR]Post2PingFM Submitter v.01\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $postdata);
        while (!feof($fp)) {
            $buf .= fgets($fp,128);
        }
        $counter++;
        fclose($fp);
    }
    return $buf;
}


//$api_key = 'faec1ae02db39b8da9fd4a528e6b2006';
//$user_key = 'c4438539bf3f28c68116d6428a40c063-1221211560';
//$message = 'testing from fsockopen';
//cr_post2pingfm_do_submit_ping($api_key, $user_key, $message);