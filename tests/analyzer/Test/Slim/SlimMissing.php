<?php

namespace Test\Slim;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class SlimMissing extends Analyzer {
    /* 1 methods */

    public function testSlim_SlimMissing01()  { $this->generic_test('Slim/SlimMissing.01'); }
}
?>