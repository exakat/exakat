<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldBeProtectedProperty extends Analyzer {
    /* 4 methods */

    public function testClasses_CouldBeProtectedProperty01()  { $this->generic_test('Classes/CouldBeProtectedProperty.01'); }
    public function testClasses_CouldBeProtectedProperty02()  { $this->generic_test('Classes/CouldBeProtectedProperty.02'); }
    public function testClasses_CouldBeProtectedProperty03()  { $this->generic_test('Classes/CouldBeProtectedProperty.03'); }
    public function testClasses_CouldBeProtectedProperty04()  { $this->generic_test('Classes/CouldBeProtectedProperty.04'); }
}
?>