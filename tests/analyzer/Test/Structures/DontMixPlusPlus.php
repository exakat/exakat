<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DontMixPlusPlus extends Analyzer {
    /* 1 methods */

    public function testStructures_DontMixPlusPlus01()  { $this->generic_test('Structures/DontMixPlusPlus.01'); }
}
?>