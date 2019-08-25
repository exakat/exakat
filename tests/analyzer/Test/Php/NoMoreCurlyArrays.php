<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoMoreCurlyArrays extends Analyzer {
    /* 1 methods */

    public function testPhp_NoMoreCurlyArrays01()  { $this->generic_test('Php/NoMoreCurlyArrays.01'); }
}
?>