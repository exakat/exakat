<?php

$expected     = array('$this->a',
                      'b::$b',
                     );

$expected_not = array('self::$a',
                      '$this->b',
                     );

?>