<?php

namespace A; 

trait t {}

interface i {}

function f() {}

const a = 2;
//define('b', 3);

use a as b;

global $y;

static $s;

include 'a';
include_once 'b';
require 'c';
require_once 'd';

?>