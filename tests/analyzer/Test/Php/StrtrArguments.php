<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class StrtrArguments extends Analyzer {
    /* 2 methods */

    public function testPhp_StrtrArguments01()  { $this->generic_test('Php/StrtrArguments.01'); }
    public function testPhp_StrtrArguments02()  { $this->generic_test('Php/StrtrArguments.02'); }
}
?>