<?php

$expected     = array('$class::Staticmethod( )',
                      '$class::$property',
                      'constant("x::constante")',
                     );

$expected_not = array('constant($class."::constante")',
                     );

?>