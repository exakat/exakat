<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ThisIsNotForStatic extends Analyzer {
    /* 2 methods */

    public function testClasses_ThisIsNotForStatic01()  { $this->generic_test('Classes_ThisIsNotForStatic.01'); }
    public function testClasses_ThisIsNotForStatic02()  { $this->generic_test('Classes_ThisIsNotForStatic.02'); }
}
?>