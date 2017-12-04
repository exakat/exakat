<?php

$expected     = array('bcscale($precision + 6)',
                      'bcdiv(1, bcsqrt(2))',
                      'bcsqrt(2)',
                      'bcdiv(bcadd($a, $b), 2)',
                      'bcadd($a, $b)',
                      'bcsqrt(bcmul($a, $b))',
                      'bcmul($a, $b)',
                      'bcsub($t, bcmul($p, bcpow(bcsub($a, $x), 2)))',
                      'bcmul($p, bcpow(bcsub($a, $x), 2))',
                      'bcpow(bcsub($a, $x), 2)',
                      'bcsub($a, $x)',
                      'bcmul(2, $p)',
                      'bcdiv(bcpow(bcadd($a, $b), 2), bcmul(4, $t), $precision)',
                      'bcpow(bcadd($a, $b), 2)',
                      'bcadd($a, $b)',
                      'bcmul(4, $t)',
                     );

$expected_not = array('bcpi',
                     );

?>