<?php

$expected     = array('parent::$x',
                      'a::$x',
                      'parent::y( )',
                      'a::y( )',
                     );

$expected_not = array('parent::$xp',
                      'a::$xp',
                      'parent::yp( )',
                      'a::yp( )',
                     );

?>