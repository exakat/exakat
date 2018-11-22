<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoDirectInputToWpdb extends Analyzer {
    /* 1 methods */

    public function testWordpress_NoDirectInputToWpdb01()  { $this->generic_test('Wordpress/NoDirectInputToWpdb.01'); }
}
?>