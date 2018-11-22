<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IndirectInjection extends Analyzer {
    /* 2 methods */

    public function testSecurity_IndirectInjection01()  { $this->generic_test('Security/IndirectInjection.01'); }
    public function testSecurity_IndirectInjection02()  { $this->generic_test('Security/IndirectInjection.02'); }
}
?>