<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoGlobalModification extends Analyzer {
    /* 2 methods */

    public function testWordpress_NoGlobalModification01()  { $this->generic_test('Wordpress_NoGlobalModification.01'); }
    public function testWordpress_NoGlobalModification02()  { $this->generic_test('Wordpress_NoGlobalModification.02'); }
}
?>