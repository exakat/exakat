<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CloseTags extends Analyzer {
    /* 6 methods */

    public function testPhp_CloseTags01()  { $this->generic_test('Php/CloseTags.01'); }
    public function testPhp_CloseTags02()  { $this->generic_test('Php/CloseTags.02'); }
    public function testPhp_CloseTags03()  { $this->generic_test('Php/CloseTags.03'); }
    public function testPhp_CloseTags04()  { $this->generic_test('Php/CloseTags.04'); }
    public function testPhp_CloseTags05()  { $this->generic_test('Php/CloseTags.05'); }
    public function testPhp_CloseTags06()  { $this->generic_test('Php/CloseTags.06'); }
}
?>