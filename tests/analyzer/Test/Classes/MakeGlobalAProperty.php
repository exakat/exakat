<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MakeGlobalAProperty extends Analyzer {
    /* 2 methods */

    public function testClasses_MakeGlobalAProperty01()  { $this->generic_test('Classes/MakeGlobalAProperty.01'); }
    public function testClasses_MakeGlobalAProperty02()  { $this->generic_test('Classes/MakeGlobalAProperty.02'); }
}
?>