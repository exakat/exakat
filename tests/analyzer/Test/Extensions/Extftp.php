<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extftp extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extftp01()  { $this->generic_test('Extensions_Extftp.01'); }
}
?>