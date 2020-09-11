<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CastUnsetUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_CastUnsetUsage01()  { $this->generic_test('Php/CastUnsetUsage.01'); }
}
?>