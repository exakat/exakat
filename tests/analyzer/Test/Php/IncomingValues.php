<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IncomingValues extends Analyzer {
    /* 1 methods */

    public function testPhp_IncomingValues01()  { $this->generic_test('Php/IncomingValues.01'); }
}
?>