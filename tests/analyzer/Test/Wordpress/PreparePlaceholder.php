<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class PreparePlaceholder extends Analyzer {
    /* 1 methods */

    public function testWordpress_PreparePlaceholder01()  { $this->generic_test('Wordpress/PreparePlaceholder.01'); }
}
?>