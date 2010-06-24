<?php

include_once('PHPingFM.php');

$api_key = 'faec1ae02db39b8da9fd4a528e6b2006';
$user_key = '7ce95bf92b77f2c502ab951a5ed8a13e-1247103536';

$pfm = new PHPingFM($api_key, $user_key, true);
$result = $pfm->post("status", "testing local API");
