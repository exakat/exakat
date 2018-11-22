<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NotInThatPath extends Analyzer {
    /* 1 methods */

    public function testZendF_NotInThatPath01()  { $this->generic_test('ZendF/NotInThatPath.01'); }
}
?>