<?php

namespace Test\Portability;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FopenMode extends Analyzer {
    /* 2 methods */

    public function testPortability_FopenMode01()  { $this->generic_test('Portability_FopenMode.01'); }
    public function testPortability_FopenMode02()  { $this->generic_test('Portability_FopenMode.02'); }
}
?>