<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CastingTernary extends Analyzer {
    /* 1 methods */

    public function testStructures_CastingTernary01()  { $this->generic_test('Structures/CastingTernary.01'); }
}
?>