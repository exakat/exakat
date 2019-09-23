<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CreateDefaultValues extends Analyzer {
    /* 2 methods */

    public function testComplete_CreateDefaultValues01()  { $this->generic_test('Complete/CreateDefaultValues.01'); }
    public function testComplete_CreateDefaultValues02()  { $this->generic_test('Complete/CreateDefaultValues.02'); }
}
?>