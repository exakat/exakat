<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SubstrToTrim extends Analyzer {
    /* 1 methods */

    public function testStructures_SubstrToTrim01()  { $this->generic_test('Structures/SubstrToTrim.01'); }
}
?>