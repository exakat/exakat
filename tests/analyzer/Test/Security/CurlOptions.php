<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CurlOptions extends Analyzer {
    /* 1 methods */

    public function testSecurity_CurlOptions01()  { $this->generic_test('Security/CurlOptions.01'); }
}
?>