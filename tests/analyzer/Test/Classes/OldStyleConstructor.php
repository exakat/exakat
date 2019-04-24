<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class OldStyleConstructor extends Analyzer {
    /* 4 methods */

    public function testClasses_OldStyleConstructor01()  { $this->generic_test('Classes_OldStyleConstructor.01'); }
    public function testClasses_OldStyleConstructor02()  { $this->generic_test('Classes_OldStyleConstructor.02'); }
    public function testClasses_OldStyleConstructor03()  { $this->generic_test('Classes_OldStyleConstructor.03'); }
    public function testClasses_OldStyleConstructor04()  { $this->generic_test('Classes_OldStyleConstructor.04'); }
}
?>