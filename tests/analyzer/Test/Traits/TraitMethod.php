<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TraitMethod extends Analyzer {
    /* 1 methods */

    public function testTraits_TraitMethod01()  { $this->generic_test('Traits_TraitMethod.01'); }
}
?>