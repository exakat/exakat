<?php

namespace Test\Traits;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnusedTrait extends Analyzer {
    /* 8 methods */

    public function testTraits_UnusedTrait01()  { $this->generic_test('Traits/UnusedTrait.01'); }
    public function testTraits_UnusedTrait02()  { $this->generic_test('Traits/UnusedTrait.02'); }
    public function testTraits_UnusedTrait03()  { $this->generic_test('Traits/UnusedTrait.03'); }
    public function testTraits_UnusedTrait04()  { $this->generic_test('Traits/UnusedTrait.04'); }
    public function testTraits_UnusedTrait05()  { $this->generic_test('Traits/UnusedTrait.05'); }
    public function testTraits_UnusedTrait06()  { $this->generic_test('Traits/UnusedTrait.06'); }
    public function testTraits_UnusedTrait07()  { $this->generic_test('Traits/UnusedTrait.07'); }
    public function testTraits_UnusedTrait08()  { $this->generic_test('Traits/UnusedTrait.08'); }
}
?>