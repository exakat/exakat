<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CantThrow extends Analyzer {
    /* 2 methods */

    public function testExceptions_CantThrow01()  { $this->generic_test('Exceptions/CantThrow.01'); }
    public function testExceptions_CantThrow02()  { $this->generic_test('Exceptions/CantThrow.02'); }
}
?>