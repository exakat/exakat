<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TrailingComma extends Analyzer {
    /* 4 methods */

    public function testPhp_TrailingComma01()  { $this->generic_test('Php/TrailingComma.01'); }
    public function testPhp_TrailingComma02()  { $this->generic_test('Php/TrailingComma.02'); }
    public function testPhp_TrailingComma03()  { $this->generic_test('Php/TrailingComma.03'); }
    public function testPhp_TrailingComma04()  { $this->generic_test('Php/TrailingComma.04'); }
}
?>