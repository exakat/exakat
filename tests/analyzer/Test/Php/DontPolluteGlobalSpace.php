<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DontPolluteGlobalSpace extends Analyzer {
    /* 1 methods */

    public function testPhp_DontPolluteGlobalSpace01()  { $this->generic_test('Php/DontPolluteGlobalSpace.01'); }
}
?>