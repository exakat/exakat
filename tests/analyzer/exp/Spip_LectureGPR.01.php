<?php

$expected     = array('$_GET[\'read1\']',
                      '$_GET[\'read21\'][\'read22\']',
                      '$_GET[\'read31\'][\'read32\'][\'read33\']',
                      '$_GET[\'read4\']',
                      '$_GET',
                      
                      '$_POST[\'read1\']',
                      '$_POST[\'read21\'][\'read22\']',
                      '$_POST[\'read31\'][\'read32\'][\'read33\']',
                      '$_POST[\'read4\']',
                      '$_POST',
                      
                      '$_REQUEST[\'read1\']',
                      '$_REQUEST[\'read21\'][\'read22\']',
                      '$_REQUEST[\'read31\'][\'read32\'][\'read33\']',
                      '$_REQUEST[\'read4\']',
                      '$_REQUEST',
);

$expected_not = array('$_GET[\'read21\']',
                      '$_POST[\'read21\']',
                      '$_REQUEST[\'read21\']',
                      '$_GET[\'inside_process_gpr\']',
                      '$_POST[\'inside_process_gpr\']',
                      '$_REQUEST[\'inside_process_gpr\']',
                      '$_GET',
                      '$_POST',
                      '$_REQUEST',
                      );

?>