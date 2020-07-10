<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldNotType extends Analyzer {
    /* 1 methods */

    public function testTypehints_CouldNotType01()  { $this->generic_test('Typehints/CouldNotType.01'); }
}
?>