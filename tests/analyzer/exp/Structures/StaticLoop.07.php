<?php

$expected     = array('while ($row2 = $res->fetchArray(2)) { /**/ } ',
                     );

$expected_not = array('while ($row = $res->fetchArray(1)) { /**/ } ',
                      'while ($row3 = $res->fetchArray($res)) { /**/ } ',
                     );

?>