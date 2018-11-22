<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Zf3Eventmanager extends Analyzer {
    /* 2 methods */

    public function testZendF_Zf3Eventmanager01()  { $this->generic_test('ZendF/Zf3Eventmanager.01'); }
    public function testZendF_Zf3Eventmanager02()  { $this->generic_test('ZendF/Zf3Eventmanager.02'); }
}
?>