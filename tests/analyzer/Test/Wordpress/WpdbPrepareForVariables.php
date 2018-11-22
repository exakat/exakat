<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class WpdbPrepareForVariables extends Analyzer {
    /* 2 methods */

    public function testWordpress_WpdbPrepareForVariables01()  { $this->generic_test('Wordpress/WpdbPrepareForVariables.01'); }
    public function testWordpress_WpdbPrepareForVariables02()  { $this->generic_test('Wordpress/WpdbPrepareForVariables.02'); }
}
?>