<?php

     header("Set-Cookie: {$name}={$value}; EXPIRES{$date};");

     header("set-Cookie: {$name}={$value}; EXPIRES{$date};");

    header('Set-Cookie: '.$name.'='.$value.'; EXPIRES'.$date.';');

     header('sat-cookie: {$name}={$value}; EXPIRES{$date};');
     
     setcookie("TestCookie3", $value, time()+3600, "/~rasmus/", "example.com", 1);


?>