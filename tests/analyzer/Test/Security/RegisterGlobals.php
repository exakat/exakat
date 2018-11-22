<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class RegisterGlobals extends Analyzer {
    /* 3 methods */

    public function testSecurity_RegisterGlobals01()  { $this->generic_test('Security/RegisterGlobals.01'); }
    public function testSecurity_RegisterGlobals02()  { $this->generic_test('Security/RegisterGlobals.02'); }
    public function testSecurity_RegisterGlobals03()  { $this->generic_test('Security/RegisterGlobals.03'); }
}
?>