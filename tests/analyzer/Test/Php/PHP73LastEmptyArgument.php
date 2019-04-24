<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PHP73LastEmptyArgument extends Analyzer {
    /* 4 methods */

    public function testPhp_PHP73LastEmptyArgument01()  { $this->generic_test('Php/PHP73LastEmptyArgument.01'); }
    public function testPhp_PHP73LastEmptyArgument02()  { $this->generic_test('Php/PHP73LastEmptyArgument.02'); }
    public function testPhp_PHP73LastEmptyArgument03()  { $this->generic_test('Php/PHP73LastEmptyArgument.03'); }
    public function testPhp_PHP73LastEmptyArgument04()  { $this->generic_test('Php/PHP73LastEmptyArgument.04'); }
}
?>