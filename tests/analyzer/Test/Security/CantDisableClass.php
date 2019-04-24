<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CantDisableClass extends Analyzer {
    /* 1 methods */

    public function testSecurity_CantDisableClass01()  { $this->generic_test('Security/CantDisableClass.01'); }
}
?>