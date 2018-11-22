<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DoublePrepare extends Analyzer {
    /* 1 methods */

    public function testWordpress_DoublePrepare01()  { $this->generic_test('Wordpress/DoublePrepare.01'); }
}
?>