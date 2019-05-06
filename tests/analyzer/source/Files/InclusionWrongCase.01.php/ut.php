<?php

include('inc/include.php');
include('INC/include.php');
include('inc/INCLUDE.php');

include('/inc/include.php');
include('/INC/include.php');
include('/inc/INCLUDE.php');

include('inc/nonexistent.php');

require 'include.php';
require 'include.PHP';

echo __FILE__;

?>