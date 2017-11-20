<?php

// array to object
$arr = [0 => 1];
$obj = (object)$arr;
var_dump(
    $obj,
    $obj->{'0'}, // PHP 7.2+ accessible
    $obj->{0}, // PHP 7.2+ accessible

    $obj->{'b'} // always been accessible
);
?>