<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AssertFunctionIsReserved extends Analyzer {
    /* 1 methods */

    public function testPhp_AssertFunctionIsReserved01()  { $this->generic_test('Php/AssertFunctionIsReserved.01'); }
}
?>