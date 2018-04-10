<?php

$expected     = array('class AWithFoo { /**/ } ', 
                      'class AWithFooAndOther { /**/ } ',
                     );

$expected_not = array('class AEmpty { /**/ } ', 
                      'class AWithFooAndArg { /**/ } ','',
                      'class AWithOtherThanFoo { /**/ } ','',
                      'class AWithFooBadAndOther { /**/ } ','',
                     );

?>