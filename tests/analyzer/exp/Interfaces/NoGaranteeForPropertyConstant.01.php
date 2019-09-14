<?php

$expected     = array('$i::$p',
                      '$i->p',
                     );

$expected_not = array('$i->m()',
                      '$i::m()',
                      '$i::c',
                     );

?>