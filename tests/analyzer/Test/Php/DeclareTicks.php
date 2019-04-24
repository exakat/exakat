<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DeclareTicks extends Analyzer {
    /* 4 methods */

    public function testPhp_DeclareTicks01()  { $this->generic_test('Php/DeclareTicks.01'); }
    public function testPhp_DeclareTicks02()  { $this->generic_test('Php/DeclareTicks.02'); }
    public function testPhp_DeclareTicks03()  { $this->generic_test('Php/DeclareTicks.03'); }
    public function testPhp_DeclareTicks04()  { $this->generic_test('Php/DeclareTicks.04'); }
}
?>