<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ThrowUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_ThrowUsage01()  { $this->generic_test('Php/ThrowUsage.01'); }
}
?>