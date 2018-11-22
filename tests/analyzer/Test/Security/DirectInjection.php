<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DirectInjection extends Analyzer {
    /* 7 methods */

    public function testSecurity_DirectInjection01()  { $this->generic_test('Security_DirectInjection.01'); }
    public function testSecurity_DirectInjection02()  { $this->generic_test('Security/DirectInjection.02'); }
    public function testSecurity_DirectInjection03()  { $this->generic_test('Security/DirectInjection.03'); }
    public function testSecurity_DirectInjection04()  { $this->generic_test('Security/DirectInjection.04'); }
    public function testSecurity_DirectInjection05()  { $this->generic_test('Security/DirectInjection.05'); }
    public function testSecurity_DirectInjection06()  { $this->generic_test('Security/DirectInjection.06'); }
    public function testSecurity_DirectInjection07()  { $this->generic_test('Security/DirectInjection.07'); }
}
?>