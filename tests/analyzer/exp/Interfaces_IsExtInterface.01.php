<?php

$expected     = array('\Reflector',
                      'Reflector', // should find one, not two
                      '\Reflector');

$expected_not = array('Reflector' // should find one, not two
                     );

?>