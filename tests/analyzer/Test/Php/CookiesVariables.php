<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CookiesVariables extends Analyzer {
    /* 2 methods */

    public function testPhp_CookiesVariables01()  { $this->generic_test('Php/CookiesVariables.01'); }
    public function testPhp_CookiesVariables02()  { $this->generic_test('Php/CookiesVariables.02'); }
}
?>