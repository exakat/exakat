<?php

     header("Set-Cookie: {$name}={$value}; EXPIRES{$date};");

     header("set-Cookie: {$name}={$value}; EXPIRES{$date};");

    header('Set-Cookie: '.$name.'='.$value.'; EXPIRES'.$date.';');

     header('sat-cookie: {$name}={$value}; EXPIRES{$date};');

?>