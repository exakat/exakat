<?php

$expected     = array('const TWO = ONE * 2',
                      'const THREE = TWO + 1',
                      'const ONE_THIRD = ONE / self::THREE',
                      'const SENTENCE = \'The value of THREE is \' . self::THREE',
                      'public function f($a = ONE + self::THREE) { /**/ } ',
                     );

$expected_not = array('const ONE = 1',
                     );

?>