<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ParenthesisAsParameter extends Analyzer {
    /* 2 methods */

    public function testPhp_ParenthesisAsParameter01()  { $this->generic_test('Php/ParenthesisAsParameter.01'); }
    public function testPhp_ParenthesisAsParameter02()  { $this->generic_test('Php/ParenthesisAsParameter.02'); }
}
?>