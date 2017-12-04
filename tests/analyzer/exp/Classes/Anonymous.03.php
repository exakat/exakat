<?php

$expected     = array('class ($i) implements i { /**/ } ',
                      'class ($i, $j) implements i { /**/ } ',
                      'class implements i { /**/ } ',
                     );

$expected_not = array('class x { /**/ } ',
                     );

?>