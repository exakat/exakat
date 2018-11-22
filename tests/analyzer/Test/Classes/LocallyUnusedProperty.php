<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class LocallyUnusedProperty extends Analyzer {
    /* 9 methods */

    public function testClasses_LocallyUnusedProperty01()  { $this->generic_test('Classes_LocallyUnusedProperty.01'); }
    public function testClasses_LocallyUnusedProperty02()  { $this->generic_test('Classes_LocallyUnusedProperty.02'); }
    public function testClasses_LocallyUnusedProperty03()  { $this->generic_test('Classes_LocallyUnusedProperty.03'); }
    public function testClasses_LocallyUnusedProperty04()  { $this->generic_test('Classes_LocallyUnusedProperty.04'); }
    public function testClasses_LocallyUnusedProperty05()  { $this->generic_test('Classes_LocallyUnusedProperty.05'); }
    public function testClasses_LocallyUnusedProperty06()  { $this->generic_test('Classes/LocallyUnusedProperty.06'); }
    public function testClasses_LocallyUnusedProperty07()  { $this->generic_test('Classes/LocallyUnusedProperty.07'); }
    public function testClasses_LocallyUnusedProperty08()  { $this->generic_test('Classes/LocallyUnusedProperty.08'); }
    public function testClasses_LocallyUnusedProperty09()  { $this->generic_test('Classes/LocallyUnusedProperty.09'); }
}
?>