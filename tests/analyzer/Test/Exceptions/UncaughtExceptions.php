<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UncaughtExceptions extends Analyzer {
    /* 3 methods */

    public function testExceptions_UncaughtExceptions01()  { $this->generic_test('Exceptions/UncaughtExceptions.01'); }
    public function testExceptions_UncaughtExceptions02()  { $this->generic_test('Exceptions/UncaughtExceptions.02'); }
    public function testExceptions_UncaughtExceptions03()  { $this->generic_test('Exceptions/UncaughtExceptions.03'); }
}
?>