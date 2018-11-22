<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AlternativeSyntax extends Analyzer {
    /* 2 methods */

    public function testPhp_AlternativeSyntax01()  { $this->generic_test('Php/AlternativeSyntax.01'); }
    public function testPhp_AlternativeSyntax02()  { $this->generic_test('Php/AlternativeSyntax.02'); }
}
?>