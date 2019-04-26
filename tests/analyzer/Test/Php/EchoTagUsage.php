<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class EchoTagUsage extends Analyzer {
    /* 2 methods */

    public function testPhp_EchoTagUsage01()  { $this->generic_test('Php/EchoTagUsage.01'); }
    public function testPhp_EchoTagUsage02()  { $this->generic_test('Php/EchoTagUsage.02'); }
}
?>