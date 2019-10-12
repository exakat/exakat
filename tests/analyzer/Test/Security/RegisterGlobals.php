<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class RegisterGlobals extends Analyzer {
    /* 5 methods */

    public function testSecurity_RegisterGlobals01()  { $this->generic_test('Security/RegisterGlobals.01'); }
    public function testSecurity_RegisterGlobals02()  { $this->generic_test('Security/RegisterGlobals.02'); }
    public function testSecurity_RegisterGlobals03()  { $this->generic_test('Security/RegisterGlobals.03'); }
    public function testSecurity_RegisterGlobals04()  { $this->generic_test('Security/RegisterGlobals.04'); }
    public function testSecurity_RegisterGlobals05()  { $this->generic_test('Security/RegisterGlobals.05'); }
}
?>