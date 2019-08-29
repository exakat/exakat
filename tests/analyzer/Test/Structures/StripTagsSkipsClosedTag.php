<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StripTagsSkipsClosedTag extends Analyzer {
    /* 2 methods */

    public function testStructures_StripTagsSkipsClosedTag01()  { $this->generic_test('Structures/StripTagsSkipsClosedTag.01'); }
    public function testStructures_StripTagsSkipsClosedTag02()  { $this->generic_test('Structures/StripTagsSkipsClosedTag.02'); }
}
?>