<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DefineWithArray extends Analyzer {
    /* 1 methods */

    public function testPhp_DefineWithArray01()  { $this->generic_test('Php/DefineWithArray.01'); }
}
?>