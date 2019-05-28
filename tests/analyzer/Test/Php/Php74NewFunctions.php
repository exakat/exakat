<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php74NewFunctions extends Analyzer {
    /* 1 methods */

    public function testPhp_Php74NewFunctions01()  { $this->generic_test('Php/Php74NewFunctions.01'); }
}
?>