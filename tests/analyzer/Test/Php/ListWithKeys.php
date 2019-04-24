<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ListWithKeys extends Analyzer {
    /* 3 methods */

    public function testPhp_ListWithKeys01()  { $this->generic_test('Php/ListWithKeys.01'); }
    public function testPhp_ListWithKeys02()  { $this->generic_test('Php/ListWithKeys.02'); }
    public function testPhp_ListWithKeys03()  { $this->generic_test('Php/ListWithKeys.03'); }
}
?>