<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class SuperGlobalUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_SuperGlobalUsage01()  { $this->generic_test('Php/SuperGlobalUsage.01'); }
}
?>