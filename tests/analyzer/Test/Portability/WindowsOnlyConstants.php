<?php

namespace Test\Portability;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WindowsOnlyConstants extends Analyzer {
    /* 1 methods */

    public function testPortability_WindowsOnlyConstants01()  { $this->generic_test('Portability/WindowsOnlyConstants.01'); }
}
?>