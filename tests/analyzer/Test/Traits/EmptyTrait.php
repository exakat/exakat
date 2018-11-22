<?php

namespace Test\Traits;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class EmptyTrait extends Analyzer {
    /* 2 methods */

    public function testTraits_EmptyTrait01()  { $this->generic_test('Traits_EmptyTrait.01'); }
    public function testTraits_EmptyTrait02()  { $this->generic_test('Traits/EmptyTrait.02'); }
}
?>