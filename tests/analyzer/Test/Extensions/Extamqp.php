<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extamqp extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extamqp01()  { $this->generic_test('Extensions/Extamqp.01'); }
}
?>