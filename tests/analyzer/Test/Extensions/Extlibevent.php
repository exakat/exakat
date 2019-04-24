<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extlibevent extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extlibevent01()  { $this->generic_test('Extensions/Extlibevent.01'); }
}
?>