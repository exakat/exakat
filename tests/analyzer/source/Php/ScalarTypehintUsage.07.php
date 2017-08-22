<?php

$a = function ($returnObject) : object {};
$a = function ($returnAbject) : abject {};

$a = function (object $object, object &$objectR, object $objectDefault = null, abject $abject){
    (function_exists('b')) ? 'b' : die('d');
};

?>