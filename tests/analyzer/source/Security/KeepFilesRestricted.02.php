<?php

chmod($f, 0770);
chmod($f, 0777);
chmod($f, -1);
chmod($f, (0777));
chmod($f, (rand(0, 1)? $a : 0777));
chmod($f, (rand(0, 1)? $a : foo()));
chmod($f, (rand(0, 1)? $a : hoo()));


function foo($a = 0777) {
    if (rand(0, 1)) {
        return $a;
    } else {
        return 0777;
    }
}

function goo($a = 0777) {
    chmod($f, $a);
}

function hoo($a = 0777) {
    return $a;
}

?>