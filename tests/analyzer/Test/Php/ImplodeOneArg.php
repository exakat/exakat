<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ImplodeOneArg extends Analyzer {
    /* 1 methods */

    public function testPhp_ImplodeOneArg01()  { $this->generic_test('Php/ImplodeOneArg.01'); }
}
?>