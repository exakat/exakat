<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsModified extends Analyzer {
    /* 9 methods */

    public function testClasses_IsModified01()  { $this->generic_test('Classes_IsModified.01'); }
    public function testClasses_IsModified02()  { $this->generic_test('Classes_IsModified.02'); }
    public function testClasses_IsModified03()  { $this->generic_test('Classes_IsModified.03'); }
    public function testClasses_IsModified04()  { $this->generic_test('Classes_IsModified.04'); }
    public function testClasses_IsModified05()  { $this->generic_test('Classes_IsModified.05'); }
    public function testClasses_IsModified06()  { $this->generic_test('Classes/IsModified.06'); }
    public function testClasses_IsModified07()  { $this->generic_test('Classes/IsModified.07'); }
    public function testClasses_IsModified08()  { $this->generic_test('Classes/IsModified.08'); }
    public function testClasses_IsModified09()  { $this->generic_test('Classes/IsModified.09'); }
}
?>