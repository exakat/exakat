<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CryptoUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_CryptoUsage01()  { $this->generic_test('Php/CryptoUsage.01'); }
}
?>