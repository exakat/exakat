<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class RawPostDataUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_RawPostDataUsage01()  { $this->generic_test('Php/RawPostDataUsage.01'); }
}
?>