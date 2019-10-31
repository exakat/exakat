<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PropagateCalls extends Analyzer {
    /* 1 methods */

    public function testComplete_PropagateCalls01()  { $this->generic_test('Complete/PropagateCalls.01'); }
}
?>