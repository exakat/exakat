<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class EmptyClass extends Analyzer {
    /* 8 methods */

    public function testClasses_EmptyClass01()  { $this->generic_test('Classes_EmptyClass.01'); }
    public function testClasses_EmptyClass02()  { $this->generic_test('Classes_EmptyClass.02'); }
    public function testClasses_EmptyClass03()  { $this->generic_test('Classes_EmptyClass.03'); }
    public function testClasses_EmptyClass04()  { $this->generic_test('Classes_EmptyClass.04'); }
    public function testClasses_EmptyClass05()  { $this->generic_test('Classes/EmptyClass.05'); }
    public function testClasses_EmptyClass06()  { $this->generic_test('Classes/EmptyClass.06'); }
    public function testClasses_EmptyClass07()  { $this->generic_test('Classes/EmptyClass.07'); }
    public function testClasses_EmptyClass08()  { $this->generic_test('Classes/EmptyClass.08'); }
}
?>