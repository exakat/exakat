<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NonceCreation extends Analyzer {
    /* 1 methods */

    public function testWordpress_NonceCreation01()  { $this->generic_test('Wordpress/NonceCreation.01'); }
}
?>