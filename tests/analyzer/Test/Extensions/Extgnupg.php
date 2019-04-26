<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extgnupg extends Analyzer {
    /* 3 methods */

    public function testExtensions_Extgnupg01()  { $this->generic_test('Extensions_Extgnupg.01'); }
    public function testExtensions_Extgnupg02()  { $this->generic_test('Extensions_Extgnupg.02'); }
    public function testExtensions_Extgnupg03()  { $this->generic_test('Extensions_Extgnupg.03'); }
}
?>