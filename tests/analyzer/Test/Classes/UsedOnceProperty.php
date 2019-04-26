<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UsedOnceProperty extends Analyzer {
    /* 3 methods */

    public function testClasses_UsedOnceProperty01()  { $this->generic_test('Classes/UsedOnceProperty.01'); }
    public function testClasses_UsedOnceProperty02()  { $this->generic_test('Classes/UsedOnceProperty.02'); }
    public function testClasses_UsedOnceProperty03()  { $this->generic_test('Classes/UsedOnceProperty.03'); }
}
?>