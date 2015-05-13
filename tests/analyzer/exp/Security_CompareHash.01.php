<?php

$expected     = array("'0' != hash('md5', '240610708', false)", 
                      "hash('md5', '240610708', false) == '0'", 
                      "elseif (hash('crc32', '2332', false)) { /**/ } ", 
                      "if (hash('alder32', '00e00099', false)) { /**/ }  else elseif (hash('crc32', '2332', false)) { /**/ } ");

$expected_not = array();

?>