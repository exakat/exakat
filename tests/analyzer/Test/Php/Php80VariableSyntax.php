<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php80VariableSyntax extends Analyzer {
    /* 2 methods */

    public function testPhp_Php80VariableSyntax01()  { $this->generic_test('Php/Php80VariableSyntax.01'); }
    public function testPhp_Php80VariableSyntax02()  { $this->generic_test('Php/Php80VariableSyntax.02'); }
}
?>