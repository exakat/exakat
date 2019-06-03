<?php

$expected     = array('self::$a',
                      'b::$b',
                      '$this->c',
                     );

$expected_not = array('$this->a',
                      '$this->b',
                     );

?>