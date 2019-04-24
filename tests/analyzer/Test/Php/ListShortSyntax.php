<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ListShortSyntax extends Analyzer {
    /* 1 methods */

    public function testPhp_ListShortSyntax01()  { $this->generic_test('Php/ListShortSyntax.01'); }
}
?>