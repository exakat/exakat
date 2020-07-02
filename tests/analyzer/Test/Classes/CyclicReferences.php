<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CyclicReferences extends Analyzer {
    /* 1 methods */

    public function testClasses_CyclicReferences01()  { $this->generic_test('Classes/CyclicReferences.01'); }
}
?>