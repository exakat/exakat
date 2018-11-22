<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class SuperGlobalContagion extends Analyzer {
    /* 1 methods */

    public function testSecurity_SuperGlobalContagion01()  { $this->generic_test('Security_SuperGlobalContagion.01'); }
}
?>