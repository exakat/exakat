<?php

$expected     = array('foreach($_FILES["pictures"]["error"] as $key => $error) { /**/ } ',
                      '"A" . $_COOKIE[\'incoming\'][\'array\'] . "B"',
                      '"A" . $_SERVER[\'incoming\'] . "B"',
                      'move_uploaded_file($_FILES[\'name\'][\'filename\'], $_FILES[\'name\'][\'destination\'])',
                      'move_uploaded_file($_FILES[\'name\'][\'filename\'], 1)',
                      'move_uploaded_file(0, $_FILES[\'name\'][\'filename\'])',
                      'echo $_GET[\'incoming\']',
                      '"{$_COOKIE[\'incoming\']}"',
                      '"{$_ENV[\'incoming1\']}"',
                      '"{$_ENV[\'incoming0\']}"',
                      '"{$_ENV[\'incoming2\']}"',
                      'include $_GET[\'x\']',
                     );

$expected_not = array('strtolower($_GET[\'variable\'])',
                      'print array_sum($_POST)',
                     );

?>