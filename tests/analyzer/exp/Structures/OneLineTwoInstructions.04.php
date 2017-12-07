<?php

$expected     = array('$this->x++',
                     );

$expected_not = array('private $x',
                      'var $a',
                      'var $b = 3',
                     );

?>