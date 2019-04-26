<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StaticMethods extends Analyzer {
    /* 2 methods */

    public function testClasses_StaticMethods01()  { $this->generic_test('Classes_StaticMethods.01'); }
    public function testClasses_StaticMethods02()  { $this->generic_test('Classes/StaticMethods.02'); }
}
?>