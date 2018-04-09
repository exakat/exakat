<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Exceptions_DefinedExceptions extends Analyzer {
    /* 3 methods */

    public function testExceptions_DefinedExceptions01()  { $this->generic_test('Exceptions_DefinedExceptions.01'); }
    public function testExceptions_DefinedExceptions02()  { $this->generic_test('Exceptions_DefinedExceptions.02'); }
    public function testExceptions_DefinedExceptions03()  { $this->generic_test('Exceptions/DefinedExceptions.03'); }
}
?>