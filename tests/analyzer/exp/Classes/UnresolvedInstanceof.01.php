<?php

$expected     = array('$a instanceof notDefinedClass',
                     );

$expected_not = array('$a instanceof a',
                      '$a instanceof \a',
                     );

?>