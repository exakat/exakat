<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php80NewFunctions extends Analyzer {
    /* 1 methods */

    public function testPhp_Php80NewFunctions01()  { $this->generic_test('Php/Php80NewFunctions.01'); }
}
?>