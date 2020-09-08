<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectForeachFavorite extends Analyzer {
    /* 2 methods */

    public function testDump_CollectForeachFavorite01()  { $this->generic_test('Dump/CollectForeachFavorite.01'); }
    public function testDump_CollectForeachFavorite02()  { $this->generic_test('Dump/CollectForeachFavorite.02'); }
}
?>