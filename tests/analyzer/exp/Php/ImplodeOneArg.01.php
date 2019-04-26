<?php

$expected     = array('implode($a)',
                     );

$expected_not = array('\a\implode($a1)',
                      '$a->implode($a2)',
                      'A::implode($a3)',
                      'implode( )',
                     );

?>