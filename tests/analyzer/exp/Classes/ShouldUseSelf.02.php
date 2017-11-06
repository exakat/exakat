<?php

$expected     = array('\A\B\x::e',
                      'x::e',
                      'x::$p',
                      '\A\B\x::$p',
                      'x::method( )',
                      '\A\B\x::method( )',
                     );

$expected_not = array('static::e',
                      'parent::e',
                     );

?>