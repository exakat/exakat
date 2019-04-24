<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Prints extends Analyzer {
    /* 1 methods */

    public function testPhp_Prints01()  { $this->generic_test('Php/Prints.01'); }
}
?>