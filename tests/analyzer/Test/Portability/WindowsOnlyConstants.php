<?php

namespace Test\Portability;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class WindowsOnlyConstants extends Analyzer {
    /* 1 methods */

    public function testPortability_WindowsOnlyConstants01()  { $this->generic_test('Portability/WindowsOnlyConstants.01'); }
}
?>