<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldPreprocess extends Analyzer {
    /* 1 methods */

    public function testPhp_ShouldPreprocess01()  { $this->generic_test('Php/ShouldPreprocess.01'); }
}
?>