<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extpdo extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extpdo01()  { $this->generic_test('Extensions_Extpdo.01'); }
    public function testExtensions_Extpdo02()  { $this->generic_test('Extensions_Extpdo.02'); }
}
?>