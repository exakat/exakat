<?php

$expected     = array('self::CONST_BAR::$baz',
                      '"foo$bar"[0]',
                      '__FUNCTION__[33]',
                     );

$expected_not = array('"dfdf"[33]',
                      'FUNCTION__[34]',
                      'self::CONST_BAR[0]::$baz',
                     );

?>