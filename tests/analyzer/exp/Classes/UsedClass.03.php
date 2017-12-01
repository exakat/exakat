<?php

$expected     = array( 'class Used { /**/ } ',
                     );

$expected_not = array( 'class UsedButUndefined { /**/ } ', 
                       'class Unused { /**/ } ',
                       'class UnusedAndUnmentionned { /**/ } ',
                       'UsedButUndefined::x',
                       'UsedClass::x::multipledoublecolonIsAnError',
                       'Methodname'
                       );

?>