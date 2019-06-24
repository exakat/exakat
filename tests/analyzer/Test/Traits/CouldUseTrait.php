<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldUseTrait extends Analyzer {
    /* 2 methods */

    public function testTraits_CouldUseTrait01()  { $this->generic_test('Traits/CouldUseTrait.01'); }
    public function testTraits_CouldUseTrait02()  { $this->generic_test('Traits/CouldUseTrait.02'); }
}
?>