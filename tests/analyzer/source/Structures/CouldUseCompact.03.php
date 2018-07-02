<?php

const C = 'D';
const E = 'DD';

$a = array(C.'D' => $DD);
$a = array(D.'D' => $DD);
$a = array(E.'D' => $DD);

$a = array(\C.'D' => $DD);
$a = array(\D.'D' => $DD);
$a = array(\E.'D' => $DD);

?>