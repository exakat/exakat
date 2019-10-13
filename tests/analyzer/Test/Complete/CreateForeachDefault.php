<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CreateForeachDefault extends Analyzer {
    /* 1 methods */

    public function testComplete_CreateForeachDefault01()  { $this->generic_test('Complete/CreateForeachDefault.01'); }
}
?>