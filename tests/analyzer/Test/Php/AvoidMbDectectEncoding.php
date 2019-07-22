<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AvoidMbDectectEncoding extends Analyzer {
    /* 1 methods */

    public function testPhp_AvoidMbDectectEncoding01()  { $this->generic_test('Php/AvoidMbDectectEncoding.01'); }
}
?>