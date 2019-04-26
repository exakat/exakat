<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UndeclaredStaticProperty extends Analyzer {
    /* 4 methods */

    public function testClasses_UndeclaredStaticProperty01()  { $this->generic_test('Classes/UndeclaredStaticProperty.01'); }
    public function testClasses_UndeclaredStaticProperty02()  { $this->generic_test('Classes/UndeclaredStaticProperty.02'); }
    public function testClasses_UndeclaredStaticProperty03()  { $this->generic_test('Classes/UndeclaredStaticProperty.03'); }
    public function testClasses_UndeclaredStaticProperty04()  { $this->generic_test('Classes/UndeclaredStaticProperty.04'); }
}
?>