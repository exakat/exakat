<?php

$expected     = array('\\uasort($a, array(\'someClass\', \'callback\'))',
                      'usort($a, function ($x, $y) { /**/ } )',
                      'uksort($a, \'callback\')',
                     );

$expected_not = array('$a->uasort($aMethod)',
                      'A::uksort($aStaticMethod)',
                     );

?>