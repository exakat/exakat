<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TryMultipleCatch extends Analyzer {
    /* 1 methods */

    public function testPhp_TryMultipleCatch01()  { $this->generic_test('Php/TryMultipleCatch.01'); }
}
?>