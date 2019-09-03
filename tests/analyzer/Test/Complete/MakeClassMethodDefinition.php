<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MakeClassMethodDefinition extends Analyzer {
    /* 1 methods */

    public function testComplete_MakeClassMethodDefinition01()  { $this->generic_test('Complete/MakeClassMethodDefinition.01'); }
}
?>