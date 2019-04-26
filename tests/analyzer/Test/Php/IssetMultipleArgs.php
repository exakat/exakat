<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IssetMultipleArgs extends Analyzer {
    /* 1 methods */

    public function testPhp_IssetMultipleArgs01()  { $this->generic_test('Php/IssetMultipleArgs.01'); }
}
?>