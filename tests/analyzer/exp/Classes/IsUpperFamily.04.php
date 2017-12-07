<?php

$expected     = array('parent::inAA( )',
                     );

$expected_not = array('parent::inA( )',
                      'parent::inB( )',
                      'parent::inTrait( )',
                      'parent::nowhere( )',
                      'c::inC( )',
                     );

?>