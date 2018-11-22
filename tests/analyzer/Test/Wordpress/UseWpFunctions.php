<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseWpFunctions extends Analyzer {
    /* 1 methods */

    public function testWordpress_UseWpFunctions01()  { $this->generic_test('Wordpress/UseWpFunctions.01'); }
}
?>