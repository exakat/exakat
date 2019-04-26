<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Overwriting extends Analyzer {
    /* 1 methods */

    public function testVariables_Overwriting01()  { $this->generic_test('Variables/Overwriting.01'); }
}
?>