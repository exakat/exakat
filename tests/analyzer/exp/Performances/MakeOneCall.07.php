<?php

$expected     = array('str_replace($a1, $b1, str_replace($a2, $b2, $c))',
                      'str_replace($a1, $b1, str_replace($a2, $b2, str_replace($a3, $b3, $c)))',
                     );

$expected_not = array('str_ireplace($a1, $b1, str_replace($a2, $b2, $c))',
                      '$str_ireplace($a1, $b1, str_replace($a2, $b2, str_replace($a3, $b3, $c)))',
                     );

?>