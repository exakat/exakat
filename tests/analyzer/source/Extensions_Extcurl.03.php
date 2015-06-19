<?php

namespace B {
    $ch = curl_init("http://www.example.com/");
    $fp = fopen("example_homepage.txt", "w");

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    function curl_setopt() {}
    function curl_init() {}
}

namespace {
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}
?>