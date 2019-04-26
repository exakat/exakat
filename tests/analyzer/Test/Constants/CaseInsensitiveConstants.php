<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CaseInsensitiveConstants extends Analyzer {
    /* 1 methods */

    public function testConstants_CaseInsensitiveConstants01()  { $this->generic_test('Constants/CaseInsensitiveConstants.01'); }
}
?>