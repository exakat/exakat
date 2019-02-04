<?php

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

defined('_AKEEBA') or die();

if (str_replace($_SERVER['REQUEST_URI'], ".inc.php")) die("go! access");

?>