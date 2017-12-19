<?php

namespace GuzzleHttp;
use  \Boris\Inspector as BR;

function fooa() : BatchResults{}  // composer

function foob() : \BatchResults{} // not composer 

function fooc() : BR{}            // composer

function food() :  BR{}              // composer

function fooe() : NotBatchResults{} // not composer 
?>