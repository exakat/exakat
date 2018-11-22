<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ReturnWithParenthesis extends Analyzer {
    /* 1 methods */

    public function testPhp_ReturnWithParenthesis01()  { $this->generic_test('Php/ReturnWithParenthesis.01'); }
}
?>