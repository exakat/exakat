<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extgeoip extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extgeoip01()  { $this->generic_test('Extensions/Extgeoip.01'); }
}
?>