<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoWeakSSLCrypto extends Analyzer {
    /* 1 methods */

    public function testSecurity_NoWeakSSLCrypto01()  { $this->generic_test('Security/NoWeakSSLCrypto.01'); }
}
?>