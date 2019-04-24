<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleTraitOrInterface extends Analyzer {
    /* 1 methods */

    public function testClasses_MultipleTraitOrInterface01()  { $this->generic_test('Classes/MultipleTraitOrInterface.01'); }
}
?>