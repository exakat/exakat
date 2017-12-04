<?php

$expected     = array('class ($i) extends i { /**/ } ',
                      'class ($i, $j) extends i { /**/ } ',
                      'class extends i { /**/ } ',
                     );

$expected_not = array('class x { /**/ } ',
                     );

?>