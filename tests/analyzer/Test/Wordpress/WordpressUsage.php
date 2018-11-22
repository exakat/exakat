<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class WordpressUsage extends Analyzer {
    /* 1 methods */

    public function testWordpress_WordpressUsage01()  { $this->generic_test('Wordpress/WordpressUsage.01'); }
}
?>