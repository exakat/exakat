<?php

namespace Test\Portability;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IconvTranslit extends Analyzer {
    /* 1 methods */

    public function testPortability_IconvTranslit01()  { $this->generic_test('Portability/IconvTranslit.01'); }
}
?>