<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoClassInGlobal extends Analyzer {
    /* 1 methods */

    public function testPhp_NoClassInGlobal01()  { $this->generic_test('Php/NoClassInGlobal.01'); }
}
?>