<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ConstantClass extends Analyzer {
    /* 3 methods */

    public function testClasses_ConstantClass01()  { $this->generic_test('Classes_ConstantClass.01'); }
    public function testClasses_ConstantClass02()  { $this->generic_test('Classes/ConstantClass.02'); }
    public function testClasses_ConstantClass03()  { $this->generic_test('Classes/ConstantClass.03'); }
}
?>