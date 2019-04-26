<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetCookieArgs extends Analyzer {
    /* 1 methods */

    public function testSecurity_SetCookieArgs01()  { $this->generic_test('Security/SetCookieArgs.01'); }
}
?>