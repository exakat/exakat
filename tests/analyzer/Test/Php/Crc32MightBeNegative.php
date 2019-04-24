<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Crc32MightBeNegative extends Analyzer {
    /* 1 methods */

    public function testPhp_Crc32MightBeNegative01()  { $this->generic_test('Php/Crc32MightBeNegative.01'); }
}
?>