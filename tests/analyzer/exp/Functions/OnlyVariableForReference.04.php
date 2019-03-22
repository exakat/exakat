<?php

$expected     = array('foo(bar2( ))', 
                      'foo(new x)',
                     );

$expected_not = array('foo($this)',
                      'foo($_SERVER)',
                      'foo(bar( ))', 
                      'foo(bar2( ))', 
                     );

?>