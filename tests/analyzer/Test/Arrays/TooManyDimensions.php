<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TooManyDimensions extends Analyzer {
    /* 1 methods */

    public function testArrays_TooManyDimensions01()  { $this->generic_test('Arrays/TooManyDimensions.01'); }
}
?>