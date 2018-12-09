<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoPSSOutsideClass extends Analyzer {
    /* 4 methods */

    public function testClasses_NoPSSOutsideClass01()  { $this->generic_test('Classes/NoPSSOutsideClass.01'); }
    public function testClasses_NoPSSOutsideClass02()  { $this->generic_test('Classes/NoPSSOutsideClass.02'); }
    public function testClasses_NoPSSOutsideClass03()  { $this->generic_test('Classes/NoPSSOutsideClass.03'); }
    public function testClasses_NoPSSOutsideClass04()  { $this->generic_test('Classes/NoPSSOutsideClass.04'); }
}
?>