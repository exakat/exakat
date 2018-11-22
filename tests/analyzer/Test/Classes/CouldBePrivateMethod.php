<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldBePrivateMethod extends Analyzer {
    /* 2 methods */

    public function testClasses_CouldBePrivateMethod01()  { $this->generic_test('Classes/CouldBePrivateMethod.01'); }
    public function testClasses_CouldBePrivateMethod02()  { $this->generic_test('Classes/CouldBePrivateMethod.02'); }
}
?>