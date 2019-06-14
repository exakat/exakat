<?php

$expected     = array('class WeakReference { /**/ } ',
                      'class ReflectionReference { /**/ } ',
                     );

$expected_not = array('class NotPHP74Class { /**/ } ',
                      'class notphp74class { /**/ } ',
                      'class reflectionreference { /**/ } ',
                      'class weakreference { /**/ } ',
                     );

?>