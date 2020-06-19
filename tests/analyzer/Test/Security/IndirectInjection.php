<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IndirectInjection extends Analyzer {
    /* 9 methods */

    public function testSecurity_IndirectInjection01()  { $this->generic_test('Security/IndirectInjection.01'); }
    public function testSecurity_IndirectInjection02()  { $this->generic_test('Security/IndirectInjection.02'); }
    public function testSecurity_IndirectInjection03()  { $this->generic_test('Security/IndirectInjection.03'); }
    public function testSecurity_IndirectInjection04()  { $this->generic_test('Security/IndirectInjection.04'); }
    public function testSecurity_IndirectInjection05()  { $this->generic_test('Security/IndirectInjection.05'); }
    public function testSecurity_IndirectInjection06()  { $this->generic_test('Security/IndirectInjection.06'); }
    public function testSecurity_IndirectInjection07()  { $this->generic_test('Security/IndirectInjection.07'); }
    public function testSecurity_IndirectInjection08()  { $this->generic_test('Security/IndirectInjection.08'); }
    public function testSecurity_IndirectInjection09()  { $this->generic_test('Security/IndirectInjection.09'); }
}
?>