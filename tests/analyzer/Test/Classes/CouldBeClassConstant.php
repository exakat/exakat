<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldBeClassConstant extends Analyzer {
    /* 7 methods */

    public function testClasses_CouldBeClassConstant01()  { $this->generic_test('Classes_CouldBeClassConstant.01'); }
    public function testClasses_CouldBeClassConstant02()  { $this->generic_test('Classes_CouldBeClassConstant.02'); }
    public function testClasses_CouldBeClassConstant03()  { $this->generic_test('Classes_CouldBeClassConstant.03'); }
    public function testClasses_CouldBeClassConstant04()  { $this->generic_test('Classes_CouldBeClassConstant.04'); }
    public function testClasses_CouldBeClassConstant05()  { $this->generic_test('Classes/CouldBeClassConstant.05'); }
    public function testClasses_CouldBeClassConstant06()  { $this->generic_test('Classes/CouldBeClassConstant.06'); }
    public function testClasses_CouldBeClassConstant07()  { $this->generic_test('Classes/CouldBeClassConstant.07'); }
}
?>