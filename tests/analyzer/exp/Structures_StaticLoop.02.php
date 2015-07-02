<?php

$expected     = array('for($i = 0 ; $i < 3 ;  ) { /**/ } ',
                      'for($i = 0 ; $i < 7 ; $i++) { /**/ } ',
);

$expected_not = array('for($i = 0 ; $i < 10 ; $i++) { /**/ } ',);

?>