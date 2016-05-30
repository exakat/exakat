<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Exceptions_UncaughtExceptions extends Analyzer {
    /* 2 methods */

    public function testExceptions_UncaughtExceptions01()  { $this->generic_test('Exceptions/UncaughtExceptions.01'); }
    public function testExceptions_UncaughtExceptions02()  { $this->generic_test('Exceptions/UncaughtExceptions.02'); }
}
?>