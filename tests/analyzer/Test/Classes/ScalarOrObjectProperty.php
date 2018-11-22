<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ScalarOrObjectProperty extends Analyzer {
    /* 3 methods */

    public function testClasses_ScalarOrObjectProperty01()  { $this->generic_test('Classes/ScalarOrObjectProperty.01'); }
    public function testClasses_ScalarOrObjectProperty02()  { $this->generic_test('Classes/ScalarOrObjectProperty.02'); }
    public function testClasses_ScalarOrObjectProperty03()  { $this->generic_test('Classes/ScalarOrObjectProperty.03'); }
}
?>