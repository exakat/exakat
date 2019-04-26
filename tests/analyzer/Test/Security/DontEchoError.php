<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DontEchoError extends Analyzer {
    /* 4 methods */

    public function testSecurity_DontEchoError01()  { $this->generic_test('Security/DontEchoError.01'); }
    public function testSecurity_DontEchoError02()  { $this->generic_test('Security/DontEchoError.02'); }
    public function testSecurity_DontEchoError03()  { $this->generic_test('Security/DontEchoError.03'); }
    public function testSecurity_DontEchoError04()  { $this->generic_test('Security/DontEchoError.04'); }
}
?>