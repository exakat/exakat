<?php

$expected     = array('$this->c',
                     );

$expected_not = array('self::$a',
                      'b::$b',
                      '$this->a',
                      '$this->b',
                     );

?>