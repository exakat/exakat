<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnserializeSecondArg extends Analyzer {
    /* 1 methods */

    public function testSecurity_UnserializeSecondArg01()  { $this->generic_test('Security/UnserializeSecondArg.01'); }
}
?>