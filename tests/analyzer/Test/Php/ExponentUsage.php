<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ExponentUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_ExponentUsage01()  { $this->generic_test('Php/ExponentUsage.01'); }
}
?>