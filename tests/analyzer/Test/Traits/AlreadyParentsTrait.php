<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AlreadyParentsTrait extends Analyzer {
    /* 3 methods */

    public function testTraits_AlreadyParentsTrait01()  { $this->generic_test('Traits/AlreadyParentsTrait.01'); }
    public function testTraits_AlreadyParentsTrait02()  { $this->generic_test('Traits/AlreadyParentsTrait.02'); }
    public function testTraits_AlreadyParentsTrait03()  { $this->generic_test('Traits/AlreadyParentsTrait.03'); }
}
?>