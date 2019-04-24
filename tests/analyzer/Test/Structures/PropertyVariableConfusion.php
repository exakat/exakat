<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PropertyVariableConfusion extends Analyzer {
    /* 5 methods */

    public function testStructures_PropertyVariableConfusion01()  { $this->generic_test('Structures_PropertyVariableConfusion.01'); }
    public function testStructures_PropertyVariableConfusion02()  { $this->generic_test('Structures_PropertyVariableConfusion.02'); }
    public function testStructures_PropertyVariableConfusion03()  { $this->generic_test('Structures/PropertyVariableConfusion.03'); }
    public function testStructures_PropertyVariableConfusion04()  { $this->generic_test('Structures/PropertyVariableConfusion.04'); }
    public function testStructures_PropertyVariableConfusion05()  { $this->generic_test('Structures/PropertyVariableConfusion.05'); }
}
?>