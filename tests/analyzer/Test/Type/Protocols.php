<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Protocols extends Analyzer {
    /* 1 methods */

    public function testType_Protocols01()  { $this->generic_test('Type/Protocols.01'); }
}
?>