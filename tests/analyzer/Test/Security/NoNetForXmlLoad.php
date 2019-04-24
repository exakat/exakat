<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoNetForXmlLoad extends Analyzer {
    /* 4 methods */

    public function testSecurity_NoNetForXmlLoad01()  { $this->generic_test('Security/NoNetForXmlLoad.01'); }
    public function testSecurity_NoNetForXmlLoad02()  { $this->generic_test('Security/NoNetForXmlLoad.02'); }
    public function testSecurity_NoNetForXmlLoad03()  { $this->generic_test('Security/NoNetForXmlLoad.03'); }
    public function testSecurity_NoNetForXmlLoad04()  { $this->generic_test('Security/NoNetForXmlLoad.04'); }
}
?>