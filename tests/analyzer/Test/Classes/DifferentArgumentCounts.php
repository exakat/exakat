<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DifferentArgumentCounts extends Analyzer {
    /* 1 methods */

    public function testClasses_DifferentArgumentCounts01()  { $this->generic_test('Classes/DifferentArgumentCounts.01'); }
}
?>