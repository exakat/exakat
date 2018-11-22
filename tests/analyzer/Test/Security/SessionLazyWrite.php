<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class SessionLazyWrite extends Analyzer {
    /* 1 methods */

    public function testSecurity_SessionLazyWrite01()  { $this->generic_test('Security/SessionLazyWrite.01'); }
}
?>