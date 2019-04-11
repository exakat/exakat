<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IndirectInjection extends Analyzer {
    /* 4 methods */

    public function testSecurity_IndirectInjection01()  { $this->generic_test('Security/IndirectInjection.01'); }
    public function testSecurity_IndirectInjection02()  { $this->generic_test('Security/IndirectInjection.02'); }
    public function testSecurity_IndirectInjection03()  { $this->generic_test('Security/IndirectInjection.03'); }
    public function testSecurity_IndirectInjection04()  { $this->generic_test('Security/IndirectInjection.04'); }
}
?>