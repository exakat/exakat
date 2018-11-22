<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ThrowUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_ThrowUsage01()  { $this->generic_test('Php/ThrowUsage.01'); }
}
?>