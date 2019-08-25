<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php74ReservedKeyword extends Analyzer {
    /* 1 methods */

    public function testPhp_Php74ReservedKeyword01()  { $this->generic_test('Php/Php74ReservedKeyword.01'); }
}
?>