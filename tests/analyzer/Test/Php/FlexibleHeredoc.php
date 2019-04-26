<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FlexibleHeredoc extends Analyzer {
    /* 1 methods */

    public function testPhp_FlexibleHeredoc01()  { $this->generic_test('Php/FlexibleHeredoc.01'); }
}
?>