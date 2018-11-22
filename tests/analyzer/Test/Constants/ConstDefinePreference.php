<?php

namespace Test\Constants;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ConstDefinePreference extends Analyzer {
    /* 3 methods */

    public function testConstants_ConstDefinePreference01()  { $this->generic_test('Constants/ConstDefinePreference.01'); }
    public function testConstants_ConstDefinePreference02()  { $this->generic_test('Constants/ConstDefinePreference.02'); }
    public function testConstants_ConstDefinePreference03()  { $this->generic_test('Constants/ConstDefinePreference.03'); }
}
?>