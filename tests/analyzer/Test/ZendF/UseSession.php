<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseSession extends Analyzer {
    /* 2 methods */

    public function testZendF_UseSession01()  { $this->generic_test('ZendF/UseSession.01'); }
    public function testZendF_UseSession02()  { $this->generic_test('ZendF/UseSession.02'); }
}
?>