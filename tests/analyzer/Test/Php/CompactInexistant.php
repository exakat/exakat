<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CompactInexistant extends Analyzer {
    /* 2 methods */

    public function testPhp_CompactInexistant01()  { $this->generic_test('Php/CompactInexistant.01'); }
    public function testPhp_CompactInexistant02()  { $this->generic_test('Php/CompactInexistant.02'); }
}
?>