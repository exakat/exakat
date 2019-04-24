<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class debugInfoUsage extends Analyzer {
    /* 2 methods */

    public function testPhp_debugInfoUsage01()  { $this->generic_test('Php/debugInfoUsage.01'); }
    public function testPhp_debugInfoUsage02()  { $this->generic_test('Php/debugInfoUsage.02'); }
}
?>