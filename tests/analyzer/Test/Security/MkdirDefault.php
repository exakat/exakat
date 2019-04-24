<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MkdirDefault extends Analyzer {
    /* 1 methods */

    public function testSecurity_MkdirDefault01()  { $this->generic_test('Security/MkdirDefault.01'); }
}
?>