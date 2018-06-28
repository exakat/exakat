<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Exceptions_CantThrow extends Analyzer {
    /* 2 methods */

    public function testExceptions_CantThrow01()  { $this->generic_test('Exceptions/CantThrow.01'); }
    public function testExceptions_CantThrow02()  { $this->generic_test('Exceptions/CantThrow.02'); }
}
?>