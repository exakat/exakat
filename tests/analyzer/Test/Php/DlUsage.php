<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DlUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_DlUsage01()  { $this->generic_test('Php/DlUsage.01'); }
}
?>