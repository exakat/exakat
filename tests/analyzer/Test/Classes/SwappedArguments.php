<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SwappedArguments extends Analyzer {
    /* 3 methods */

    public function testClasses_SwappedArguments01()  { $this->generic_test('Classes/SwappedArguments.01'); }
    public function testClasses_SwappedArguments02()  { $this->generic_test('Classes/SwappedArguments.02'); }
    public function testClasses_SwappedArguments03()  { $this->generic_test('Classes/SwappedArguments.03'); }
}
?>