<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class BetterRand extends Analyzer {
    /* 2 methods */

    public function testPhp_BetterRand01()  { $this->generic_test('Php/BetterRand.01'); }
    public function testPhp_BetterRand02()  { $this->generic_test('Php/BetterRand.02'); }
}
?>