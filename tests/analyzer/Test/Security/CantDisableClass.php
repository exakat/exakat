<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CantDisableClass extends Analyzer {
    /* 1 methods */

    public function testSecurity_CantDisableClass01()  { $this->generic_test('Security/CantDisableClass.01'); }
}
?>