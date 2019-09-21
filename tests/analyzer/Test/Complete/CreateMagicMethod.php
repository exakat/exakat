<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CreateMagicMethod extends Analyzer {
    /* 1 methods */

    public function testComplete_CreateMagicMethod01()  { $this->generic_test('Complete/CreateMagicMethod.01'); }
}
?>