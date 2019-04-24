<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php72ObjectKeyword extends Analyzer {
    /* 1 methods */

    public function testPhp_Php72ObjectKeyword01()  { $this->generic_test('Php/Php72ObjectKeyword.01'); }
}
?>