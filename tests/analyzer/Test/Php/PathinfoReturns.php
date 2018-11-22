<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class PathinfoReturns extends Analyzer {
    /* 2 methods */

    public function testPhp_PathinfoReturns01()  { $this->generic_test('Php/PathinfoReturns.01'); }
    public function testPhp_PathinfoReturns02()  { $this->generic_test('Php/PathinfoReturns.02'); }
}
?>