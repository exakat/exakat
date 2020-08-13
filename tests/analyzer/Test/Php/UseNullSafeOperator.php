<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseNullSafeOperator extends Analyzer {
    /* 1 methods */

    public function testPhp_UseNullSafeOperator01()  { $this->generic_test('Php/UseNullSafeOperator.01'); }
}
?>