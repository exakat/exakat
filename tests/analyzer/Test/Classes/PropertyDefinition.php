<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PropertyDefinition extends Analyzer {
    /* 1 methods */

    public function testClasses_PropertyDefinition01()  { $this->generic_test('Classes_PropertyDefinition.01'); }
    public function testClasses_PropertyDefinition02()  { $this->generic_test('Classes_PropertyDefinition.02'); }
}
?>