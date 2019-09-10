<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NestedTernaryWithoutParenthesis extends Analyzer {
    /* 1 methods */

    public function testPhp_NestedTernaryWithoutParenthesis01()  { $this->generic_test('Php/NestedTernaryWithoutParenthesis.01'); }
}
?>