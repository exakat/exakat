<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class LocallyUsedProperty extends Analyzer {
    /* 8 methods */

    public function testClasses_LocallyUsedProperty01()  { $this->generic_test('Classes_LocallyUsedProperty.01'); }
    public function testClasses_LocallyUsedProperty02()  { $this->generic_test('Classes_LocallyUsedProperty.02'); }
    public function testClasses_LocallyUsedProperty03()  { $this->generic_test('Classes_LocallyUsedProperty.03'); }
    public function testClasses_LocallyUsedProperty04()  { $this->generic_test('Classes/LocallyUsedProperty.04'); }
    public function testClasses_LocallyUsedProperty05()  { $this->generic_test('Classes/LocallyUsedProperty.05'); }
    public function testClasses_LocallyUsedProperty06()  { $this->generic_test('Classes/LocallyUsedProperty.06'); }
    public function testClasses_LocallyUsedProperty07()  { $this->generic_test('Classes/LocallyUsedProperty.07'); }
    public function testClasses_LocallyUsedProperty08()  { $this->generic_test('Classes/LocallyUsedProperty.08'); }
}
?>