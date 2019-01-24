<?php

$expected     = array('$this->f(2, 3, 4)',
                      '$this->f( )',
                     );

$expected_not = array('$this->f(1 )',
                      '$this->f(2, 3)',
                     );

?>