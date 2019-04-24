<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IncomingVariables extends Analyzer {
    /* 1 methods */

    public function testPhp_IncomingVariables01()  { $this->generic_test('Php/IncomingVariables.01'); }
}
?>