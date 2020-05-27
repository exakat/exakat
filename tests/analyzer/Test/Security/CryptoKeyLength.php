<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CryptoKeyLength extends Analyzer {
    /* 2 methods */

    public function testSecurity_CryptoKeyLength01()  { $this->generic_test('Security/CryptoKeyLength.01'); }
    public function testSecurity_CryptoKeyLength02()  { $this->generic_test('Security/CryptoKeyLength.02'); }
}
?>