<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Zf3Json extends Analyzer {
    /* 2 methods */

    public function testZendF_Zf3Json01()  { $this->generic_test('ZendF/Zf3Json.01'); }
    public function testZendF_Zf3Json02()  { $this->generic_test('ZendF/Zf3Json.02'); }
}
?>