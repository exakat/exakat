<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Zf3Test extends Analyzer {
    /* 2 methods */

    public function testZendF_Zf3Test01()  { $this->generic_test('ZendF/Zf3Test.01'); }
    public function testZendF_Zf3Test02()  { $this->generic_test('ZendF/Zf3Test.02'); }
}
?>