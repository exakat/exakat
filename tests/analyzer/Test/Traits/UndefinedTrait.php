<?php

namespace Test\Traits;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UndefinedTrait extends Analyzer {
    /* 2 methods */

    public function testTraits_UndefinedTrait01()  { $this->generic_test('Traits/UndefinedTrait.01'); }
    public function testTraits_UndefinedTrait02()  { $this->generic_test('Traits/UndefinedTrait.02'); }
}
?>