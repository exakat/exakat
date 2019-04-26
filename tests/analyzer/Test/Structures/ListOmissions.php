<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ListOmissions extends Analyzer {
    /* 1 methods */

    public function testStructures_ListOmissions01()  { $this->generic_test('Structures_ListOmissions.01'); }
}
?>