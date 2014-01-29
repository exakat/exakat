<?php

$expected     = array('finfo_open(FILEINFO_MIME_TYPE)', 
                      'finfo_file($finfo, $filename)', 
                      'finfo_close($finfo)');

$expected_not = array();

?>