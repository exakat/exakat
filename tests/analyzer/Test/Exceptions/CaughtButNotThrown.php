<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Exceptions_CaughtButNotThrown extends Analyzer {
    /* 2 methods */

    public function testExceptions_CaughtButNotThrown01()  { $this->generic_test('Exceptions/CaughtButNotThrown.01'); }
    public function testExceptions_CaughtButNotThrown02()  { $this->generic_test('Exceptions/CaughtButNotThrown.02'); }
}
?>