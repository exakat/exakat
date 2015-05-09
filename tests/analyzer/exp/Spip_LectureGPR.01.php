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

                      '$_GET[\'inside_process_gpr\']',
                      '$_POST[\'inside_process_gpr\']',
);

$expected_not = array('$_GET[\'read21\']',
                      '$_POST[\'read21\']',
                      '$_REQUEST[\'read21\']',
                      '$_REQUEST[\'inside_process_gpr\']',
                      '$_GET',
                      '$_POST',
                      '$_REQUEST',

                      '$_REQUEST[\'read1\']',
                      '$_REQUEST[\'read21\'][\'read22\']',
                      '$_REQUEST[\'read31\'][\'read32\'][\'read33\']',
                      '$_REQUEST[\'read4\']',
                      '$_REQUEST',

                      '$_GET[\'inside__request\']',
                      '$_POST[\'inside__request\']',
                      '$_REQUEST[\'inside__request\']',
                      );

?>