<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MinusOneOnError extends Analyzer {
    /* 1 methods */

    public function testSecurity_MinusOneOnError01()  { $this->generic_test('Security/MinusOneOnError.01'); }
}
?>