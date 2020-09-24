<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UndefinedTrait extends Analyzer {
    /* 4 methods */

    public function testTraits_UndefinedTrait01()  { $this->generic_test('Traits/UndefinedTrait.01'); }
    public function testTraits_UndefinedTrait02()  { $this->generic_test('Traits/UndefinedTrait.02'); }
    public function testTraits_UndefinedTrait03()  { $this->generic_test('Traits/UndefinedTrait.03'); }
    public function testTraits_UndefinedTrait04()  { $this->generic_test('Traits/UndefinedTrait.04'); }
}
?>