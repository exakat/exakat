<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class PrivateFunctionUsage extends Analyzer {
    /* 1 methods */

    public function testWordpress_PrivateFunctionUsage01()  { $this->generic_test('Wordpress/PrivateFunctionUsage.01'); }
}
?>