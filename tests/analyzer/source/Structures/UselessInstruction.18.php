<?php

function foo(&$r) {
    return $r++;
}

function foo2($v) {
    return $v++;
}

$a = function () use ($uv){
    return $uv++;
};

$a = function () use (&$ur){
    return $ur++;
};

$a = function () use (&$ur){
    return $local++;
}




?>