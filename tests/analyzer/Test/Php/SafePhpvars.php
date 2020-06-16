<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SafePhpvars extends Analyzer {
    /* 1 methods */

    public function testPhp_SafePhpvars01()  { $this->generic_test('Php/SafePhpvars.01'); }
}
?>