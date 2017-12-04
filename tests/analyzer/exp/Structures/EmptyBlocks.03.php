<?php

$expected     = array('while ($d) { /**/ } ',
                      'while ($a) :  /**/  endwhile',
                     );

$expected_not = array('while ($c) { /**/ } ',
                      'while ($b) : /**/  endwhile',
                     );

?>