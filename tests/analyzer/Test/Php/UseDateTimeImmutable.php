<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseDateTimeImmutable extends Analyzer {
    /* 1 methods */

    public function testPhp_UseDateTimeImmutable01()  { $this->generic_test('Php/UseDateTimeImmutable.01'); }
}
?>