<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extncurses extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extncurses01()  { $this->generic_test('Extensions/Extncurses.01'); }
}
?>