<?php

namespace GuzzleHttp;
use  \Boris\Inspector as BR;

class a implements  BatchResults{}  // composer

class b implements  \BatchResults{} // not composer 

class c implements  BR{}            // composer

class d implements  BR{}              // composer

class e implements  NotBatchResults{} // not composer 
?>