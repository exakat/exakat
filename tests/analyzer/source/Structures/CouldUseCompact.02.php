<?php

const C = 'D';
const D = 'DD';

$a = array(C => $D);
$a = array(D => $D);
$a = array(E => $D);

$a = array(\C => $D);
$a = array(\D => $D);
$a = array(\E => $D);

?>