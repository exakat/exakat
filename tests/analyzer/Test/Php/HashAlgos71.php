<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class HashAlgos71 extends Analyzer {
    /* 1 methods */

    public function testPhp_HashAlgos7101()  { $this->generic_test('Php/HashAlgos71.01'); }
}
?>