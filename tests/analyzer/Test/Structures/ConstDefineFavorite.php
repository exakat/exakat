<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ConstDefineFavorite extends Analyzer {
    /* 2 methods */

    public function testStructures_ConstDefineFavorite01()  { $this->generic_test('Structures/ConstDefineFavorite.01'); }
    public function testStructures_ConstDefineFavorite02()  { $this->generic_test('Structures/ConstDefineFavorite.02'); }
}
?>