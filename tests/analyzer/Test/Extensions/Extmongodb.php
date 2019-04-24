<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extmongodb extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extmongodb01()  { $this->generic_test('Extensions/Extmongodb.01'); }
}
?>