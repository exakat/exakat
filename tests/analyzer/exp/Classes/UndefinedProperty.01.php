<?php

$expected     = array('$this->undefined',
                      'x::$y',
                     );

$expected_not = array('$this->undefinedButMagic',
                      '$y->undefinedButNotInternal',
                     );

?>