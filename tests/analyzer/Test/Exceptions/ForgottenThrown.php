<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ForgottenThrown extends Analyzer {
    /* 2 methods */

    public function testExceptions_ForgottenThrown01()  { $this->generic_test('Exceptions/ForgottenThrown.01'); }
    public function testExceptions_ForgottenThrown02()  { $this->generic_test('Exceptions/ForgottenThrown.02'); }
}
?>