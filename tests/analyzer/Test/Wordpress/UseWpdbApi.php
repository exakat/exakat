<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseWpdbApi extends Analyzer {
    /* 1 methods */

    public function testWordpress_UseWpdbApi01()  { $this->generic_test('Wordpress/UseWpdbApi.01'); }
}
?>