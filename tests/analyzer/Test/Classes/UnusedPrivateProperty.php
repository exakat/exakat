<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnusedPrivateProperty extends Analyzer {
    /* 5 methods */

    public function testClasses_UnusedPrivateProperty01()  { $this->generic_test('Classes_UnusedPrivateProperty.01'); }
    public function testClasses_UnusedPrivateProperty02()  { $this->generic_test('Classes_UnusedPrivateProperty.02'); }
    public function testClasses_UnusedPrivateProperty03()  { $this->generic_test('Classes/UnusedPrivateProperty.03'); }
    public function testClasses_UnusedPrivateProperty04()  { $this->generic_test('Classes/UnusedPrivateProperty.04'); }
    public function testClasses_UnusedPrivateProperty05()  { $this->generic_test('Classes/UnusedPrivateProperty.05'); }
}
?>