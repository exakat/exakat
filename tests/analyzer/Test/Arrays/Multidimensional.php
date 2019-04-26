<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Multidimensional extends Analyzer {
    /* 1 methods */

    public function testArrays_Multidimensional01()  { $this->generic_test('Arrays/Multidimensional.01'); }
}
?>