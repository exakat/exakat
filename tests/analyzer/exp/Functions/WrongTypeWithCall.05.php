<?php

$expected     = array('$this->foo2(\'e\')',
                     );

$expected_not = array('$this->foo(\'d\')',
                      '$this->foo2(new D)',
                      '$this->foo2(new X)',
                     );

?>