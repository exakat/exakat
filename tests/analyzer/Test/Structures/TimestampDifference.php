<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TimestampDifference extends Analyzer {
    /* 1 methods */

    public function testStructures_TimestampDifference01()  { $this->generic_test('Structures/TimestampDifference.01'); }
}
?>