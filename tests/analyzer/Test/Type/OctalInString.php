<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class OctalInString extends Analyzer {
    /* 1 methods */

    public function testType_OctalInString01()  { $this->generic_test('Type/OctalInString.01'); }
}
?>