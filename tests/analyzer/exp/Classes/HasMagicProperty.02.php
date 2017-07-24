<?php

$expected     = array('class { /**/ } ', 
                      'class { /**/ } ',
                      );

$expected_not = array('class { /**/ } ',  // identical. Only two should be reported
                      );
?>