<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UsedPrivateProperty extends Analyzer {
    /* 4 methods */

    public function testClasses_UsedPrivateProperty01()  { $this->generic_test('Classes_UsedPrivateProperty.01'); }
    public function testClasses_UsedPrivateProperty02()  { $this->generic_test('Classes/UsedPrivateProperty.02'); }
    public function testClasses_UsedPrivateProperty03()  { $this->generic_test('Classes/UsedPrivateProperty.03'); }
    public function testClasses_UsedPrivateProperty04()  { $this->generic_test('Classes/UsedPrivateProperty.04'); }
}
?>