<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnusedPrivateMethod extends Analyzer {
    /* 3 methods */

    public function testClasses_UnusedPrivateMethod01()  { $this->generic_test('Classes_UnusedPrivateMethod.01'); }
    public function testClasses_UnusedPrivateMethod02()  { $this->generic_test('Classes/UnusedPrivateMethod.02'); }
    public function testClasses_UnusedPrivateMethod03()  { $this->generic_test('Classes/UnusedPrivateMethod.03'); }
}
?>