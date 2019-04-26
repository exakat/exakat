<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Traitnames extends Analyzer {
    /* 1 methods */

    public function testTraits_Traitnames01()  { $this->generic_test('Traits_Traitnames.01'); }
}
?>