<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IncludeUsage extends Analyzer {
    /* 3 methods */

    public function testStructures_IncludeUsage01()  { $this->generic_test('Structures_IncludeUsage.01'); }
    public function testStructures_IncludeUsage02()  { $this->generic_test('Structures/IncludeUsage.02'); }
    public function testStructures_IncludeUsage03()  { $this->generic_test('Structures/IncludeUsage.03'); }
}
?>