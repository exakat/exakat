<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class TryMultipleCatch extends Analyzer {
    /* 1 methods */

    public function testPhp_TryMultipleCatch01()  { $this->generic_test('Php/TryMultipleCatch.01'); }
}
?>