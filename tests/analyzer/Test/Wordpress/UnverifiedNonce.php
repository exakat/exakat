<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnverifiedNonce extends Analyzer {
    /* 1 methods */

    public function testWordpress_UnverifiedNonce01()  { $this->generic_test('Wordpress/UnverifiedNonce.01'); }
}
?>