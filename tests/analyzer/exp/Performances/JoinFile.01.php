<?php

$expected     = array('file($file5)', 
                      'file($file6)', 
                      'join(\'sb\', \file($file2))', 
                      'implode(\'\', file($file1))');

$expected_not = array();

?>