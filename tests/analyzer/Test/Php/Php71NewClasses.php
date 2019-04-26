<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php71NewClasses extends Analyzer {
    /* 1 methods */

    public function testPhp_Php71NewClasses01()  { $this->generic_test('Php/Php71NewClasses.01'); }
}
?>