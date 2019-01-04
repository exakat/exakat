<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ArrayKeyExistsSpeedup extends Analyzer {
    /* 1 methods */

    public function testPerformances_ArrayKeyExistsSpeedup01()  { $this->generic_test('Performances/ArrayKeyExistsSpeedup.01'); }
}
?>