<?php

$expected     = array("include_once ( 'ecrire.php')");

$expected_not = array("include 'inc_version.php'",
                      "require('../inc_version.php')",
                      "require_once _DIR_RESTREINT_ABS.'inc_version.php'");

?>