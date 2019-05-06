<?php

$expected     = array('implode($a)',
                      '\implode($aa)',
                     );

$expected_not = array('\a\implode($a1)',
                      '$a->implode($a2)',
                      'A::implode($a3)',
                      'implode( )',
                     );

?>