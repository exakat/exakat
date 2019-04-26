<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TryCatchUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_TryCatchUsage01()  { $this->generic_test('Php/TryCatchUsage.01'); }
}
?>