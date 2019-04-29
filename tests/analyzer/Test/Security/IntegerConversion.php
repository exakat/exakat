<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IntegerConversion extends Analyzer {
    /* 3 methods */

    public function testSecurity_IntegerConversion01()  { $this->generic_test('Security/IntegerConversion.01'); }
    public function testSecurity_IntegerConversion02()  { $this->generic_test('Security/IntegerConversion.02'); }
    public function testSecurity_IntegerConversion03()  { $this->generic_test('Security/IntegerConversion.03'); }
}
?>