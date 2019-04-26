<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AutoloadUsage extends Analyzer {
    /* 2 methods */

    public function testPhp_AutoloadUsage01()  { $this->generic_test('Php/AutoloadUsage.01'); }
    public function testPhp_AutoloadUsage02()  { $this->generic_test('Php/AutoloadUsage.02'); }
}
?>