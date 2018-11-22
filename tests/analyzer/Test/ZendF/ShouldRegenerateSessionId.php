<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ShouldRegenerateSessionId extends Analyzer {
    /* 2 methods */

    public function testZendF_ShouldRegenerateSessionId01()  { $this->generic_test('ZendF/ShouldRegenerateSessionId.01'); }
    public function testZendF_ShouldRegenerateSessionId02()  { $this->generic_test('ZendF/ShouldRegenerateSessionId.02'); }
}
?>