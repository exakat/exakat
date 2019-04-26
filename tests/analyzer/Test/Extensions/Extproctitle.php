<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extproctitle extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extproctitle01()  { $this->generic_test('Extensions/Extproctitle.01'); }
}
?>