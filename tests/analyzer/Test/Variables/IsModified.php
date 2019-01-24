<?php

namespace Test\Variables;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IsModified extends Analyzer {
    /* 8 methods */

    public function testVariables_IsModified01()  { $this->generic_test('Variables_IsModified.01'); }
    public function testVariables_IsModified02()  { $this->generic_test('Variables_IsModified.02'); }
    public function testVariables_IsModified03()  { $this->generic_test('Variables_IsModified.03'); }
    public function testVariables_IsModified04()  { $this->generic_test('Variables_IsModified.04'); }
    public function testVariables_IsModified05()  { $this->generic_test('Variables_IsModified.05'); }
    public function testVariables_IsModified06()  { $this->generic_test('Variables/IsModified.06'); }
    public function testVariables_IsModified07()  { $this->generic_test('Variables/IsModified.07'); }
    public function testVariables_IsModified08()  { $this->generic_test('Variables/IsModified.08'); }
}
?>