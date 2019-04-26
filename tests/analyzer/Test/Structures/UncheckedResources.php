<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UncheckedResources extends Analyzer {
    /* 8 methods */

    public function testStructures_UncheckedResources01()  { $this->generic_test('Structures_UncheckedResources.01'); }
    public function testStructures_UncheckedResources02()  { $this->generic_test('Structures_UncheckedResources.02'); }
    public function testStructures_UncheckedResources03()  { $this->generic_test('Structures_UncheckedResources.03'); }
    public function testStructures_UncheckedResources04()  { $this->generic_test('Structures_UncheckedResources.04'); }
    public function testStructures_UncheckedResources05()  { $this->generic_test('Structures_UncheckedResources.05'); }
    public function testStructures_UncheckedResources06()  { $this->generic_test('Structures/UncheckedResources.06'); }
    public function testStructures_UncheckedResources07()  { $this->generic_test('Structures/UncheckedResources.07'); }
    public function testStructures_UncheckedResources08()  { $this->generic_test('Structures/UncheckedResources.08'); }
}
?>