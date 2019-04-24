<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DlUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_DlUsage01()  { $this->generic_test('Php/DlUsage.01'); }
}
?>