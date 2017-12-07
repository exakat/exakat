<?php

$expected     = array('$this[3]',
                     );

$expected_not = array('$this[$n]',
                      '$this[]',
                      '$this->f',
                     );

?>