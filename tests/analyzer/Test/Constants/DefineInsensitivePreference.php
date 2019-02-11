<?php

namespace Test\Constants;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DefineInsensitivePreference extends Analyzer {
    /* 2 methods */

    public function testConstants_DefineInsensitivePreference01()  { $this->generic_test('Constants/DefineInsensitivePreference.01'); }
    public function testConstants_DefineInsensitivePreference02()  { $this->generic_test('Constants/DefineInsensitivePreference.02'); }
}
?>