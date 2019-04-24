<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class RedefinedProperty extends Analyzer {
    /* 4 methods */

    public function testClasses_RedefinedProperty01()  { $this->generic_test('Classes_RedefinedProperty.01'); }
    public function testClasses_RedefinedProperty02()  { $this->generic_test('Classes/RedefinedProperty.02'); }
    public function testClasses_RedefinedProperty03()  { $this->generic_test('Classes/RedefinedProperty.03'); }
    public function testClasses_RedefinedProperty04()  { $this->generic_test('Classes/RedefinedProperty.04'); }
}
?>