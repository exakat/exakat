<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class JsonWithOption extends Analyzer {
    /* 1 methods */

    public function testStructures_JsonWithOption01()  { $this->generic_test('Structures/JsonWithOption.01'); }
}
?>