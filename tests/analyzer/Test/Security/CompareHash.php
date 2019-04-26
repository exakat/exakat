<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CompareHash extends Analyzer {
    /* 1 methods */

    public function testSecurity_CompareHash01()  { $this->generic_test('Security_CompareHash.01'); }
}
?>