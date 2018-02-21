<?php

const C = 'D';
const D = 'DD';

$a = array(C.'D' => $DD);
$a = array(D.'D' => $DD);
$a = array(E.'D' => $DD);

$a = array(\C.'D' => $DD);
$a = array(\D.'D' => $DD);
$a = array(\E.'D' => $DD);

?>