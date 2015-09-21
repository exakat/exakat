<?php

$expected     = array('htmlentities($miss_12, \ENT_QUOTES, , \'UTF-8\')',
                      'htmlentities($miss_14, \ENT_QUOTES | \ENT_COMPAT)', 
                      'htmlentities($miss_15, ENT_QUOTES | \ENT_COMPAT)', 
                      'htmlentities($miss_11, ENT_QUOTES)', 
                      'htmlentities($miss_13, ENT_QUOTES | ENT_COMPAT)', 
                      'htmlentities($miss_16, ENT_QUOTES | \ENT_COMPAT)', 
                      'htmlentities($miss_2)');

$expected_not = array();

?>