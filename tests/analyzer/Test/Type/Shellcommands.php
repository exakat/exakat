<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Shellcommands extends Analyzer {
    /* 1 methods */

    public function testType_Shellcommands01()  { $this->generic_test('Type/Shellcommands.01'); }
}
?>