<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldBeStatic extends Analyzer {
    /* 3 methods */

    public function testClasses_CouldBeStatic01()  { $this->generic_test('Classes/CouldBeStatic.01'); }
    public function testClasses_CouldBeStatic02()  { $this->generic_test('Classes/CouldBeStatic.02'); }
    public function testClasses_CouldBeStatic03()  { $this->generic_test('Classes/CouldBeStatic.03'); }
}
?>