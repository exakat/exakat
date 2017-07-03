<?php

$expected     = array('$a++',);

$expected_not = array('switch($b) { /**/ } ',
                      'foreach ($b as $c) { /**/ } ',
                      );

?>