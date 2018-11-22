<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class FilterInputSource extends Analyzer {
    /* 1 methods */

    public function testSecurity_FilterInputSource01()  { $this->generic_test('Security/FilterInputSource.01'); }
}
?>