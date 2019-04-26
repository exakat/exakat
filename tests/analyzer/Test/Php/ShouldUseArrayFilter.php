<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldUseArrayFilter extends Analyzer {
    /* 1 methods */

    public function testPhp_ShouldUseArrayFilter01()  { $this->generic_test('Php/ShouldUseArrayFilter.01'); }
}
?>