<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MissingSubpattern extends Analyzer {
    /* 2 methods */

    public function testPhp_MissingSubpattern01()  { $this->generic_test('Php/MissingSubpattern.01'); }
    public function testPhp_MissingSubpattern02()  { $this->generic_test('Php/MissingSubpattern.02'); }
}
?>