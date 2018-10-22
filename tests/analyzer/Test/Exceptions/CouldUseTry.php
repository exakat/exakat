<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CouldUseTry extends Analyzer {
    /* 4 methods */

    public function testExceptions_CouldUseTry01()  { $this->generic_test('Exceptions/CouldUseTry.01'); }
    public function testExceptions_CouldUseTry02()  { $this->generic_test('Exceptions/CouldUseTry.02'); }
    public function testExceptions_CouldUseTry03()  { $this->generic_test('Exceptions/CouldUseTry.03'); }
    public function testExceptions_CouldUseTry04()  { $this->generic_test('Exceptions/CouldUseTry.04'); }
}
?>