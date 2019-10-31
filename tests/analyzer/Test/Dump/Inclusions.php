<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Inclusions extends Analyzer {
    /* 1 methods */

    public function testDump_Inclusions01()  { $this->generic_test('Dump/Inclusions.01'); }
}
?>