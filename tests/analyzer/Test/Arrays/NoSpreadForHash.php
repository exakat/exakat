<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoSpreadForHash extends Analyzer {
    /* 1 methods */

    public function testArrays_NoSpreadForHash01()  { $this->generic_test('Arrays/NoSpreadForHash.01'); }
}
?>