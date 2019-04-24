<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class GtOrLtFavorite extends Analyzer {
    /* 2 methods */

    public function testStructures_GtOrLtFavorite01()  { $this->generic_test('Structures/GtOrLtFavorite.01'); }
    public function testStructures_GtOrLtFavorite02()  { $this->generic_test('Structures/GtOrLtFavorite.02'); }
}
?>