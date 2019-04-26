<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class OneIfIsSufficient extends Analyzer {
    /* 1 methods */

    public function testStructures_OneIfIsSufficient01()  { $this->generic_test('Structures/OneIfIsSufficient.01'); }
}
?>