<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UsedPrivateProperty extends Analyzer {
    /* 7 methods */

    public function testClasses_UsedPrivateProperty01()  { $this->generic_test('Classes_UsedPrivateProperty.01'); }
    public function testClasses_UsedPrivateProperty02()  { $this->generic_test('Classes/UsedPrivateProperty.02'); }
    public function testClasses_UsedPrivateProperty03()  { $this->generic_test('Classes/UsedPrivateProperty.03'); }
    public function testClasses_UsedPrivateProperty04()  { $this->generic_test('Classes/UsedPrivateProperty.04'); }
    public function testClasses_UsedPrivateProperty05()  { $this->generic_test('Classes/UsedPrivateProperty.05'); }
    public function testClasses_UsedPrivateProperty06()  { $this->generic_test('Classes/UsedPrivateProperty.06'); }
    public function testClasses_UsedPrivateProperty07()  { $this->generic_test('Classes/UsedPrivateProperty.07'); }
}
?>