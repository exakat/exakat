<?php

namespace GuzzleHttp;
use  GuzzleHttp\ClientInterface as BR;

interface a extends  clientinterface {}  // composer

interface b extends  \clientInterface {} // not composer 

interface c extends  BR {}            // composer

interface d extends  \BR {}              // not composer

interface e extends  NotBatchResults {} // not composer 
?>