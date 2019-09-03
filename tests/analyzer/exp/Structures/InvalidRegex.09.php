<?php

$expected     = array('preg_match(\'/[\' . CONST_CONST2 . \'-44]\', $r, $x)',
                      'preg_match(\'/[\' . CONST_CONST . \'-44]\', $r, $x)',
                     );

$expected_not = array('preg_match(\'/[\' . CONST_CONST . \'-44]\', $r, $x)',
                      'preg_match(\'/^http[s]?:\\/\\/(:\' . CONST_DEFINE . \')?\\/.*$/Ui\', $r, $x)',
                      'preg_match(\'/^http[s]?:\\/\\/(:\' . 443 . \')?\\/.*$/Ui\', $r, $x)',
                     );

?>