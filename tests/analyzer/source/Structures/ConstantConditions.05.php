<?php

if ( !function_exists('a')) { $a++; } 

if ( !defined('B')) { $b++; } 

if ( strtolower('C')) { $b++; } 
if ( srand('C')) { $b++; } 
if ( mt_rand('C')) { $b++; } 

// Testing for void
if ( ini_get_all()) { $b++; } 

?>