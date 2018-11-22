<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extzookeeper extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extzookeeper01()  { $this->generic_test('Extensions/Extzookeeper.01'); }
}
?>