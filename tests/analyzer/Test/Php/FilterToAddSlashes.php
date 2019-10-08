<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FilterToAddSlashes extends Analyzer {
    /* 1 methods */

    public function testPhp_FilterToAddSlashes01()  { $this->generic_test('Php/FilterToAddSlashes.01'); }
}
?>