<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class SetCookieArgs extends Analyzer {
    /* 1 methods */

    public function testSecurity_SetCookieArgs01()  { $this->generic_test('Security/SetCookieArgs.01'); }
}
?>