<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ThrownExceptions extends Analyzer {
    /* 1 methods */

    public function testZendF_ThrownExceptions01()  { $this->generic_test('ZendF/ThrownExceptions.01'); }
}
?>