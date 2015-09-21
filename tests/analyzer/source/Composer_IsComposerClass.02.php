<?php

namespace GuzzleHttp;
use  GuzzleHttp\BatchResults as BR;

$a = new BatchResults();  // composer

$b = new \BatchResults(); // not composer 

$c = new BR();            // composer

$d = new BR;              // composer

$e = new NotBatchResults() // not composer 
?>