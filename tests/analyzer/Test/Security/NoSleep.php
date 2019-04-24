<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoSleep extends Analyzer {
    /* 1 methods */

    public function testSecurity_NoSleep01()  { $this->generic_test('Security_NoSleep.01'); }
}
?>