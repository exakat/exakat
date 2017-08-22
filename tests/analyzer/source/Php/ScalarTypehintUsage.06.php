<?php

function returnObject() : object {}
function returnAbject() : abject {}

function a(object $object, object &$objectR, object $objectDefault = null, abject $abject){
    (function_exists('b')) ? 'b' : die('d');
}

?>