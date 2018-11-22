<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AmbiguousStatic extends Analyzer {
    /* 5 methods */

    public function testClasses_AmbiguousStatic01()  { $this->generic_test('Classes/AmbiguousStatic.01'); }
    public function testClasses_AmbiguousStatic02()  { $this->generic_test('Classes/AmbiguousStatic.02'); }
    public function testClasses_AmbiguousStatic03()  { $this->generic_test('Classes/AmbiguousStatic.03'); }
    public function testClasses_AmbiguousStatic04()  { $this->generic_test('Classes/AmbiguousStatic.04'); }
    public function testClasses_AmbiguousStatic05()  { $this->generic_test('Classes/AmbiguousStatic.05'); }
}
?>