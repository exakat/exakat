<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoPSSOutsideClass extends Analyzer {
    /* 2 methods */

    public function testClasses_NoPSSOutsideClass01()  { $this->generic_test('Classes/NoPSSOutsideClass.01'); }
    public function testClasses_NoPSSOutsideClass02()  { $this->generic_test('Classes/NoPSSOutsideClass.02'); }
}
?>