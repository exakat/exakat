<?php

$expected     = array('$b::p ?: D', 
                      '$b ?: false',
                     );

$expected_not = array('$b->p ?: TRUE',
                      '$a ?: null',
                     );

?>