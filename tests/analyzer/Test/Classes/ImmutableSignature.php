<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ImmutableSignature extends Analyzer {
    /* 1 methods */

    public function testClasses_ImmutableSignature01()  { $this->generic_test('Classes/ImmutableSignature.01'); }
}
?>