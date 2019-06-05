<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IdenticalMethods extends Analyzer {
    /* 2 methods */

    public function testClasses_IdenticalMethods01()  { $this->generic_test('Classes/IdenticalMethods.01'); }
    public function testClasses_IdenticalMethods02()  { $this->generic_test('Classes/IdenticalMethods.02'); }
}
?>