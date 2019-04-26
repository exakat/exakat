<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ReturnTypehintUsage extends Analyzer {
    /* 4 methods */

    public function testPhp_ReturnTypehintUsage01()  { $this->generic_test('Php/ReturnTypehintUsage.01'); }
    public function testPhp_ReturnTypehintUsage02()  { $this->generic_test('Php/ReturnTypehintUsage.02'); }
    public function testPhp_ReturnTypehintUsage03()  { $this->generic_test('Php/ReturnTypehintUsage.03'); }
    public function testPhp_ReturnTypehintUsage04()  { $this->generic_test('Php/ReturnTypehintUsage.04'); }
}
?>