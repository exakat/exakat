<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extsuhosin extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extsuhosin01()  { $this->generic_test('Extensions/Extsuhosin.01'); }
}
?>