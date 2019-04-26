<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UsesEnv extends Analyzer {
    /* 1 methods */

    public function testPhp_UsesEnv01()  { $this->generic_test('Php/UsesEnv.01'); }
}
?>