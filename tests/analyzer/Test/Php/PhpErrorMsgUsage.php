<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PhpErrorMsgUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_PhpErrorMsgUsage01()  { $this->generic_test('Php/PhpErrorMsgUsage.01'); }
}
?>