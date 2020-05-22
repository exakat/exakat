<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class OpensslCipher extends Analyzer {
    /* 1 methods */

    public function testType_OpensslCipher01()  { $this->generic_test('Type/OpensslCipher.01'); }
}
?>