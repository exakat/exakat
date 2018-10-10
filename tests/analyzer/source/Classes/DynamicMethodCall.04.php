<?php

$method = 'method';
$object->$method();
$object->${method}();
$object->{$method}();
$object->$method[2]();
$object->${'method'}[2]();
$object->method();

$class::$method[2]();

?>