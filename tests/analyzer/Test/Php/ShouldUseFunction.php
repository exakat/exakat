<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldUseFunction extends Analyzer {
    /* 1 methods */

    public function testPhp_ShouldUseFunction01()  { $this->generic_test('Php/ShouldUseFunction.01'); }
}
?>