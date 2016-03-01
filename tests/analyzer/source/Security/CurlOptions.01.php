<?php

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 1);

$options = array(CURLOPT_SSL_VERIFYPEER => 0);
$options = [CURLOPT_SSL_VERIFYPEER => 0];
$options = array(CURLOPT_URL => 'a');

$options = array(\CURLOPT_SSL_VERIFYPEER => 0);
$options = [\CURLOPT_SSL_VERIFYPEER => 0];
$options = array(\CURLOPT_URL => 'a');
