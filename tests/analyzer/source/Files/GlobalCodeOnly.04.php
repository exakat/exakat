<?php

namespace X;

// Those will fallback
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

function set_error_handler() {}

// This shouldn't fallback
set_error_handler();

trait t {}

const YES = 1; 

echo YES;

?>