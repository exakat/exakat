<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StaticMethodsCalledFromObject extends Analyzer {
    /* 4 methods */

    public function testClasses_StaticMethodsCalledFromObject01()  { $this->generic_test('Classes_StaticMethodsCalledFromObject.01'); }
    public function testClasses_StaticMethodsCalledFromObject02()  { $this->generic_test('Classes/StaticMethodsCalledFromObject.02'); }
    public function testClasses_StaticMethodsCalledFromObject03()  { $this->generic_test('Classes/StaticMethodsCalledFromObject.03'); }
    public function testClasses_StaticMethodsCalledFromObject04()  { $this->generic_test('Classes/StaticMethodsCalledFromObject.04'); }
}
?>