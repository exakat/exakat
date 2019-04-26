<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class LogicalInLetters extends Analyzer {
    /* 4 methods */

    public function testPhp_LogicalInLetters01()  { $this->generic_test('Php/LogicalInLetters.01'); }
    public function testPhp_LogicalInLetters02()  { $this->generic_test('Php/LogicalInLetters.02'); }
    public function testPhp_LogicalInLetters03()  { $this->generic_test('Php/LogicalInLetters.03'); }
    public function testPhp_LogicalInLetters04()  { $this->generic_test('Php/LogicalInLetters.04'); }
}
?>