<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoReturnForGenerator extends Analyzer {
    /* 1 methods */

    public function testPhp_NoReturnForGenerator01()  { $this->generic_test('Php/NoReturnForGenerator.01'); }
}
?>