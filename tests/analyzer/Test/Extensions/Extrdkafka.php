<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extrdkafka extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extrdkafka01()  { $this->generic_test('Extensions/Extrdkafka.01'); }
}
?>