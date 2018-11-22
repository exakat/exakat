<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class GPRAliases extends Analyzer {
    /* 1 methods */

    public function testSecurity_GPRAliases01()  { $this->generic_test('Security/GPRAliases.01'); }
}
?>