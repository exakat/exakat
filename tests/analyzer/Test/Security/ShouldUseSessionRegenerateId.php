<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ShouldUseSessionRegenerateId extends Analyzer {
    /* 2 methods */

    public function testSecurity_ShouldUseSessionRegenerateId01()  { $this->generic_test('Security/ShouldUseSessionRegenerateId.01'); }
    public function testSecurity_ShouldUseSessionRegenerateId02()  { $this->generic_test('Security/ShouldUseSessionRegenerateId.02'); }
}
?>