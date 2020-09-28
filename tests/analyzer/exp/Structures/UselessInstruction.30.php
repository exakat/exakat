<?php

$expected     = array('$b::p ?? E',
                     );

$expected_not = array('$b->p ?: TRUE',
                      '$a ?: null',
                      '$b::p ?: D',
                      '$b ?: false',
                     );

?>