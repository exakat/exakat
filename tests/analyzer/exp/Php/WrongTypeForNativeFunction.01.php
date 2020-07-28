<?php

$expected     = array('log("3")',
                      'log(array( ))',
                      'exp(\'3\')',
                      'log(foo( ))',
                      'log(strtolower(\'3\'))',
                     );

$expected_not = array('log(exp(\'3\'));',
                     );

?>