<?php

$expected     = array('str_replace($a1, $b1, str_replace($a2, $b2, str_replace($a3, $b3, $c)))',
                      'str_replace($a1, $b1, str_replace($a2, $b2, $c))',
                     );

$expected_not = array('str_replace($a2, $b2, str_replace($a3, $b3, $c))',
                      'str_replace($a1, $b1, str_ireplace($a2, $b2, preg_replace($a3, $b3, $c)))',
                     );

?>