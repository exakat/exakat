<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DynamicDl extends Analyzer {
    /* 1 methods */

    public function testSecurity_DynamicDl01()  { $this->generic_test('Security/DynamicDl.01'); }
}
?>