<?php

$expected     = array('class unthrownB extends \\RuntimeException { /**/ } ',
                      'class unthrownA extends \\RuntimeException { /**/ } ',
                     );

$expected_not = array('class b extends \\RuntimeException { /**/ } ',
                      'class a extends \\Exception { /**/ } ',
                     );

?>