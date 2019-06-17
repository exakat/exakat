<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldUseCoalesce extends Analyzer {
    /* 2 methods */

    public function testPhp_ShouldUseCoalesce01()  { $this->generic_test('Php/ShouldUseCoalesce.01'); }
    public function testPhp_ShouldUseCoalesce02()  { $this->generic_test('Php/ShouldUseCoalesce.02'); }
}
?>