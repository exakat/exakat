<?php

trait t {
    function returnObject() : object {}
}

interface i {
    function returnAbject() : abject;
}

class c {
    function a(object $object, object &$objectR, object $objectDefault = null, abject $abject){
        (function_exists('b')) ? 'b' : die('d');
    }
}

?>