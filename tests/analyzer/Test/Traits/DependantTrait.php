<?php

namespace Test\Traits;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DependantTrait extends Analyzer {
    /* 10 methods */

    public function testTraits_DependantTrait01()  { $this->generic_test('Traits/DependantTrait.01'); }
    public function testTraits_DependantTrait02()  { $this->generic_test('Traits/DependantTrait.02'); }
    public function testTraits_DependantTrait03()  { $this->generic_test('Traits/DependantTrait.03'); }
    public function testTraits_DependantTrait04()  { $this->generic_test('Traits/DependantTrait.04'); }
    public function testTraits_DependantTrait05()  { $this->generic_test('Traits/DependantTrait.05'); }
    public function testTraits_DependantTrait06()  { $this->generic_test('Traits/DependantTrait.06'); }
    public function testTraits_DependantTrait07()  { $this->generic_test('Traits/DependantTrait.07'); }
    public function testTraits_DependantTrait08()  { $this->generic_test('Traits/DependantTrait.08'); }
    public function testTraits_DependantTrait09()  { $this->generic_test('Traits/DependantTrait.09'); }
    public function testTraits_DependantTrait10()  { $this->generic_test('Traits/DependantTrait.10'); }
}
?>