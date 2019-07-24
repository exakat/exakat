<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ArrayKeyExistsWithObjects extends Analyzer {
    /* 1 methods */

    public function testPhp_ArrayKeyExistsWithObjects01()  { $this->generic_test('Php/ArrayKeyExistsWithObjects.01'); }
}
?>