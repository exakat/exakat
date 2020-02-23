<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ParameterArgumentsLinks extends Analyzer {
    /* 1 methods */

    public function testDump_ParameterArgumentsLinks01()  { $this->generic_test('Dump/ParameterArgumentsLinks.01'); }
}
?>