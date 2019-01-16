<?php

$expected     = array('throw new G( )',
                     );

$expected_not = array('throw A::$E',
                      'throw $c->d[2]',
                      'throw $a->b',
                      'throw new B( )',
                      'throw new C( )',
                     );

?>