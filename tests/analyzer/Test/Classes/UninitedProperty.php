<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UninitedProperty extends Analyzer {
    /* 2 methods */

    public function testClasses_UninitedProperty01()  { $this->generic_test('Classes/UninitedProperty.01'); }
    public function testClasses_UninitedProperty02()  { $this->generic_test('Classes/UninitedProperty.02'); }
}
?>