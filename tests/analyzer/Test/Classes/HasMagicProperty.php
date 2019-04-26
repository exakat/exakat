<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class HasMagicProperty extends Analyzer {
    /* 3 methods */

    public function testClasses_HasMagicProperty01()  { $this->generic_test('Classes_HasMagicProperty.01'); }
    public function testClasses_HasMagicProperty02()  { $this->generic_test('Classes/HasMagicProperty.02'); }
    public function testClasses_HasMagicProperty03()  { $this->generic_test('Classes/HasMagicProperty.03'); }
}
?>