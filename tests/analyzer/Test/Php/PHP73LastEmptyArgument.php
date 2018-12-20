<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class PHP73LastEmptyArgument extends Analyzer {
    /* 3 methods */

    public function testPhp_PHP73LastEmptyArgument01()  { $this->generic_test('Php/PHP73LastEmptyArgument.01'); }
    public function testPhp_PHP73LastEmptyArgument02()  { $this->generic_test('Php/PHP73LastEmptyArgument.02'); }
    public function testPhp_PHP73LastEmptyArgument03()  { $this->generic_test('Php/PHP73LastEmptyArgument.03'); }
}
?>