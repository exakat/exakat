<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Zf3Inputfilter extends Analyzer {
    /* 2 methods */

    public function testZendF_Zf3Inputfilter01()  { $this->generic_test('ZendF/Zf3Inputfilter.01'); }
    public function testZendF_Zf3Inputfilter02()  { $this->generic_test('ZendF/Zf3Inputfilter.02'); }
}
?>