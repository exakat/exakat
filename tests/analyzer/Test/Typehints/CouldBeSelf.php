<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeSelf extends Analyzer {
    /* 1 methods */

    public function testTypehints_CouldBeSelf01()  { $this->generic_test('Typehints/CouldBeSelf.01'); }
}
?>