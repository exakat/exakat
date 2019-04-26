<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NormalMethods extends Analyzer {
    /* 2 methods */

    public function testClasses_NormalMethods01()  { $this->generic_test('Classes/NormalMethods.01'); }
    public function testClasses_NormalMethods02()  { $this->generic_test('Classes/NormalMethods.02'); }
}
?>