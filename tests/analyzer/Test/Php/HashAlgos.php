<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class HashAlgos extends Analyzer {
    /* 1 methods */

    public function testPhp_HashAlgos01()  { $this->generic_test('Php/HashAlgos.01'); }
}
?>