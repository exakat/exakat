<?php

const EMPTY1 = '';
const EMPTY2 = '' . EMPTY1;
const EMPTY3 = EMPTY2 . '';
const EMPTY4 = EMPTY3 . NULL;
const NOT_EMPTY = 0;
const NOT_EMPTY2 = EMPTY4 . 'd';

$e = EMPTY1 . $e;
$e = EMPTY2 . $e;
$e = EMPTY3 . $e;
$e = EMPTY4 . $e;
$e = NOT_EMPTY . $e;
$e = NOT_EMPTY2 . $e;

?>