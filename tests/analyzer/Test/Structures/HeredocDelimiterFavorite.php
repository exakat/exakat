<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class HeredocDelimiterFavorite extends Analyzer {
    /* 1 methods */

    public function testStructures_HeredocDelimiterFavorite01()  { $this->generic_test('Structures/HeredocDelimiterFavorite.01'); }
}
?>