<?php

if ( !function_exists('a')) { $a++; } 

if ( !defined('B')) { $b++; } 

if ( mt_rand('C')) { $b++; } 

// Testing for void
if ( ini_get_all()) { $b++; } 

?>