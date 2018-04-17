<?php

const A = 'include.PHP';
const B = 'include.php';

include _FILE_OPTIONS;
include PHP_VERSION;
include A;
include B;
include 'include.php';
include 'include.PHP';

?>