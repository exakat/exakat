<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ReservedKeywords7 extends Analyzer {
    /* 1 methods */

    public function testPhp_ReservedKeywords701()  { $this->generic_test('Php/ReservedKeywords7.01'); }
}
?>