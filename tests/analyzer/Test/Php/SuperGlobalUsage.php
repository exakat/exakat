<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SuperGlobalUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_SuperGlobalUsage01()  { $this->generic_test('Php/SuperGlobalUsage.01'); }
}
?>