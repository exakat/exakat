<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Exceptions_ThrownExceptions extends Analyzer {
    /* 1 methods */

    public function testExceptions_ThrownExceptions01()  { $this->generic_test('Exceptions_ThrownExceptions.01'); }
}
?>