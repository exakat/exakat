<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoGlob extends Analyzer {
    /* 1 methods */

    public function testPerformances_NoGlob01()  { $this->generic_test('Performances/NoGlob.01'); }
}
?>