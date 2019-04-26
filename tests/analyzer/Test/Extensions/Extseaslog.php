<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extseaslog extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extseaslog01()  { $this->generic_test('Extensions/Extseaslog.01'); }
}
?>