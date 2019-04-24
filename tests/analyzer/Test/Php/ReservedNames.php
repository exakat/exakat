<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ReservedNames extends Analyzer {
    /* 2 methods */

    public function testPhp_ReservedNames01()  { $this->generic_test('Php/ReservedNames.01'); }
    public function testPhp_ReservedNames02()  { $this->generic_test('Php/ReservedNames.02'); }
}
?>