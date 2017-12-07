<?php

$expected     = array('class Unused { /**/ } ',
                     );

$expected_not = array('class Used { /**/ } ',
                      'class UsedButUndefined { /**/ } ',
                     );

?>