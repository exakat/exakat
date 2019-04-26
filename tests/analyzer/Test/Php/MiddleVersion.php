<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MiddleVersion extends Analyzer {
    /* 1 methods */

    public function testPhp_MiddleVersion01()  { $this->generic_test('Php/MiddleVersion.01'); }
}
?>