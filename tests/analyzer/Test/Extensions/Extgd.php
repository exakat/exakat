<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extgd extends Analyzer {
    /* 4 methods */

    public function testExtensions_Extgd01()  { $this->generic_test('Extensions_Extgd.01'); }
    public function testExtensions_Extgd02()  { $this->generic_test('Extensions_Extgd.02'); }
    public function testExtensions_Extgd03()  { $this->generic_test('Extensions_Extgd.03'); }
    public function testExtensions_Extgd04()  { $this->generic_test('Extensions_Extgd.04'); }
}
?>