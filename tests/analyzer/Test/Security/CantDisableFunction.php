<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CantDisableFunction extends Analyzer {
    /* 3 methods */

    public function testSecurity_CantDisableFunction01()  { $this->generic_test('Security/CantDisableFunction.01'); }
    public function testSecurity_CantDisableFunction02()  { $this->generic_test('Security/CantDisableFunction.02'); }
    public function testSecurity_CantDisableFunction03()  { $this->generic_test('Security/CantDisableFunction.03'); }
}
?>