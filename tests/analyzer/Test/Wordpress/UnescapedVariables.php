<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnescapedVariables extends Analyzer {
    /* 3 methods */

    public function testWordpress_UnescapedVariables01()  { $this->generic_test('Wordpress/UnescapedVariables.01'); }
    public function testWordpress_UnescapedVariables02()  { $this->generic_test('Wordpress/UnescapedVariables.02'); }
    public function testWordpress_UnescapedVariables03()  { $this->generic_test('Wordpress/UnescapedVariables.03'); }
}
?>