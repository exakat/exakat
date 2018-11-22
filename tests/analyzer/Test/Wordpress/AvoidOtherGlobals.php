<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AvoidOtherGlobals extends Analyzer {
    /* 1 methods */

    public function testWordpress_AvoidOtherGlobals01()  { $this->generic_test('Wordpress/AvoidOtherGlobals.01'); }
}
?>