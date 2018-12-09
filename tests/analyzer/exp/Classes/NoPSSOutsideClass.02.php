<?php

$expected     = array('$a instanceof self',
                      '$a instanceof PARENT',
                      '$a instanceof static',
                      'function foo3(parent $ac) { /**/ } ',
                     );

$expected_not = array('$ac instanceof self',
                      '$ac instanceof PARENT',
                      '$ac instanceof static',
                      '$at instanceof self',
                      '$at instanceof PARENT',
                      '$at instanceof static',
                     );

?>