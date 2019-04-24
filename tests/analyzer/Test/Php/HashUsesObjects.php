<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class HashUsesObjects extends Analyzer {
    /* 1 methods */

    public function testPhp_HashUsesObjects01()  { $this->generic_test('Php/HashUsesObjects.01'); }
}
?>