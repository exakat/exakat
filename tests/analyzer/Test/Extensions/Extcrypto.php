<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extcrypto extends Analyzer {
    /* 4 methods */

    public function testExtensions_Extcrypto01()  { $this->generic_test('Extensions_Extcrypto.01'); }
    public function testExtensions_Extcrypto02()  { $this->generic_test('Extensions_Extcrypto.02'); }
    public function testExtensions_Extcrypto03()  { $this->generic_test('Extensions_Extcrypto.03'); }
    public function testExtensions_Extcrypto04()  { $this->generic_test('Extensions/Extcrypto.04'); }
}
?>