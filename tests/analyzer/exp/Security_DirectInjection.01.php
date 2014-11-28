<?php

$expected     = array('foreach($_FILES["pictures"]["error"] as $key => $error) { /**/ } ',
                      '"A" . $_COOKIE[\'incoming\'][\'array\'] . "B"',
                      '"A" . $_SERVER[\'incoming\'] . "B"',
                      '$_COOKIE[\'incoming\']',
                      'move_uploaded_file($_FILES[\'name\'][\'filename\'], $_FILES[\'name\'][\'filename\'])',
                      'move_uploaded_file($_FILES[\'name\'][\'filename\'], $_FILES[\'name\'][\'filename\'])',
                      'echo $_GET[\'incoming\']',
);

$expected_not = array();

?>