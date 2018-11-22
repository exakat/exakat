<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CompareHash extends Analyzer {
    /* 1 methods */

    public function testSecurity_CompareHash01()  { $this->generic_test('Security_CompareHash.01'); }
}
?>