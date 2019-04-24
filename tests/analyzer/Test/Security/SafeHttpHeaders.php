<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SafeHttpHeaders extends Analyzer {
    /* 2 methods */

    public function testSecurity_SafeHttpHeaders01()  { $this->generic_test('Security/SafeHttpHeaders.01'); }
    public function testSecurity_SafeHttpHeaders02()  { $this->generic_test('Security/SafeHttpHeaders.02'); }
}
?>