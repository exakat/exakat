<?php

if(!defined('A'))die;

defined('B') or die("b");

if(!defined('C'))die;
if(!defined('D')){exit;}

if(!defined('E')){ die('c') ;}

defined('F') or die('f');

if(!defined("G")){ die("g"); }

if (!defined("H")) { die("h"); }

if (!defined('I')) die;
defined('J') or die('i');

defined('K') or define('K', 1);

?>