<?php

namespace Test\Variables;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class References extends Analyzer {
    /* 1 methods */

    public function testVariables_References01()  { $this->generic_test('Variables_References.01'); }
}
?>