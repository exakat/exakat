<?php

$expected     = array('y::b(1)',
                     );

$expected_not = array('UndefinedClass::Yes( )',
                      'self::b( )',
                      'y::b( )',
                      'parent::__construct($a)',
                     );

?>