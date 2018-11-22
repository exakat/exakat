<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ConfigureExtract extends Analyzer {
    /* 1 methods */

    public function testSecurity_ConfigureExtract01()  { $this->generic_test('Security/ConfigureExtract.01'); }
}
?>