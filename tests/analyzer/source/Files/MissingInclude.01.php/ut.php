<?php

include 'a.php';
include 'b.php';

require 'c/a.php';
require('c/b.php');

include_once 'c/d/a.php';
include_once('c/d/b.php');

require_once 'c/d/e/a.php';
require_once('c/d/e/b.php');
