<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoEntIgnore extends Analyzer {
    /* 1 methods */

    public function testSecurity_NoEntIgnore01()  { $this->generic_test('Security/NoEntIgnore.01'); }
}
?>