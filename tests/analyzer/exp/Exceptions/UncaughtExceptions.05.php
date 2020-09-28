<?php

$expected     = array('throw rand(0, 1) ? new D( ) : new E( )',
                      'throw new F( )',
                      'throw $a',
                     );

$expected_not = array('throw new C1( )',
                      'throw new A( )',
                      'throw new B( )',
                      'throw new C( )',
                     );

?>