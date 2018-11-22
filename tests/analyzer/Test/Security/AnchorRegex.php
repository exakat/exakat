<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AnchorRegex extends Analyzer {
    /* 2 methods */

    public function testSecurity_AnchorRegex01()  { $this->generic_test('Security/AnchorRegex.01'); }
    public function testSecurity_AnchorRegex02()  { $this->generic_test('Security/AnchorRegex.02'); }
}
?>