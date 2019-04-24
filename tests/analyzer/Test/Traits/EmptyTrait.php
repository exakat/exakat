<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class EmptyTrait extends Analyzer {
    /* 4 methods */

    public function testTraits_EmptyTrait01()  { $this->generic_test('Traits_EmptyTrait.01'); }
    public function testTraits_EmptyTrait02()  { $this->generic_test('Traits/EmptyTrait.02'); }
    public function testTraits_EmptyTrait03()  { $this->generic_test('Traits/EmptyTrait.03'); }
    public function testTraits_EmptyTrait04()  { $this->generic_test('Traits/EmptyTrait.04'); }
}
?>