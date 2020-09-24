<?php

$expected     = array('$this->foo(\'d\')',
                      '$this->foo2(new D)',
                     );

$expected_not = array('$this->foo(\'e\')',
                      '$this->foo2(new X)',
                     );

?>