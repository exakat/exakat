<?php

$expected     = array('y::b( )',
                     );

$expected_not = array('UndefinedClass::Yes( )',
                      'self::b( )',
                      'parent::__construct($a)',
                     );

?>