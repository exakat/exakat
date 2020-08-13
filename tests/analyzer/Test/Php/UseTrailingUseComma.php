<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseTrailingUseComma extends Analyzer {
    /* 1 methods */

    public function testPhp_UseTrailingUseComma01()  { $this->generic_test('Php/UseTrailingUseComma.01'); }
}
?>