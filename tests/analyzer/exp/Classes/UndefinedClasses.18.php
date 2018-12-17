<?php

$expected     = array('PARENT::A( )',
                      'parent::A( )',
                     );

$expected_not = array('self::A( )',
                      'static::A( )',
                      'SELF::A( )',
                      'STATIC::A( )',
                     );

?>