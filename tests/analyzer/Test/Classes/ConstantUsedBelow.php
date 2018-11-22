<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ConstantUsedBelow extends Analyzer {
    /* 3 methods */

    public function testClasses_ConstantUsedBelow01()  { $this->generic_test('Classes/ConstantUsedBelow.01'); }
    public function testClasses_ConstantUsedBelow02()  { $this->generic_test('Classes/ConstantUsedBelow.02'); }
    public function testClasses_ConstantUsedBelow03()  { $this->generic_test('Classes/ConstantUsedBelow.03'); }
}
?>