<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TooManyDereferencing extends Analyzer {
    /* 2 methods */

    public function testClasses_TooManyDereferencing01()  { $this->generic_test('Classes/TooManyDereferencing.01'); }
    public function testClasses_TooManyDereferencing02()  { $this->generic_test('Classes/TooManyDereferencing.02'); }
}
?>