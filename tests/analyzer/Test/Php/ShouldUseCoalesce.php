<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldUseCoalesce extends Analyzer {
    /* 1 methods */

    public function testPhp_ShouldUseCoalesce01()  { $this->generic_test('Php/ShouldUseCoalesce.01'); }
}
?>