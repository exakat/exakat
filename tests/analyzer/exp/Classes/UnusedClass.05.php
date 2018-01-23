<?php

$expected     = array('class Unused { /**/ } ',
                     );

$expected_not = array('class Used { /**/ } ',
                      'class Used2 { /**/ } ',
                      'class UsedButUndefined { /**/ } ',
                     );

?>