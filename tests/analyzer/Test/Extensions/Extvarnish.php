<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extvarnish extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extvarnish01()  { $this->generic_test('Extensions/Extvarnish.01'); }
}
?>