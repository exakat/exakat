<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DynamicDl extends Analyzer {
    /* 1 methods */

    public function testSecurity_DynamicDl01()  { $this->generic_test('Security/DynamicDl.01'); }
}
?>