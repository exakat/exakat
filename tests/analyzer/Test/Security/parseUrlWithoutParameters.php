<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class parseUrlWithoutParameters extends Analyzer {
    /* 2 methods */

    public function testSecurity_parseUrlWithoutParameters01()  { $this->generic_test('Security_parseUrlWithoutParameters.01'); }
    public function testSecurity_parseUrlWithoutParameters02()  { $this->generic_test('Security/parseUrlWithoutParameters.02'); }
}
?>