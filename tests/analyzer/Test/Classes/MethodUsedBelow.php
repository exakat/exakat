<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MethodUsedBelow extends Analyzer {
    /* 4 methods */

    public function testClasses_MethodUsedBelow01()  { $this->generic_test('Classes/MethodUsedBelow.01'); }
    public function testClasses_MethodUsedBelow02()  { $this->generic_test('Classes/MethodUsedBelow.02'); }
    public function testClasses_MethodUsedBelow03()  { $this->generic_test('Classes/MethodUsedBelow.03'); }
    public function testClasses_MethodUsedBelow04()  { $this->generic_test('Classes/MethodUsedBelow.04'); }
}
?>