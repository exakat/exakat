<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CastingUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_CastingUsage01()  { $this->generic_test('Php/CastingUsage.01'); }
}
?>