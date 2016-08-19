<?php

namespace A\B\C {
trait t {}

interface i {}

function f() {}

const a = 2;
//define('b', 3);
// Define is not yet ready to fallback on \define

use a as b;

global $y;

static $s;

include 'a';
include_once 'b';
require 'c';
require_once 'd';
}

namespace A\B\C2 {
trait t {}

interface i {}

function f() {}

const a = 2;
//define('b', 3);
// Define is not yet ready to fallback on \define

use a as b;

global $y;

static $s;

include 'a';
include_once 'b';
require 'c';
require_once 'd';
}

namespace A\B\C3 {
trait t {}

interface i {}

function f() {}

const a = 2;
//define('b', 3);
// Define is not yet ready to fallback on \define

use a as b;

global $y;

static $s;

include 'a';
include_once 'b';
require 'c';
require_once 'd';
}

?>