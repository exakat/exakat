<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class HashAlgos74 extends Analyzer {
    /* 1 methods */

    public function testPhp_HashAlgos7401()  { $this->generic_test('Php/HashAlgos74.01'); }
}
?>