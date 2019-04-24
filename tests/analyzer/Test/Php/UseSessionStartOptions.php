<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseSessionStartOptions extends Analyzer {
    /* 1 methods */

    public function testPhp_UseSessionStartOptions01()  { $this->generic_test('Php/UseSessionStartOptions.01'); }
}
?>