<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DeclareEncoding extends Analyzer {
    /* 1 methods */

    public function testPhp_DeclareEncoding01()  { $this->generic_test('Php/DeclareEncoding.01'); }
}
?>