<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Exceptions_OverwriteException extends Analyzer {
    /* 2 methods */

    public function testExceptions_OverwriteException01()  { $this->generic_test('Exceptions_OverwriteException.01'); }
    public function testExceptions_OverwriteException02()  { $this->generic_test('Exceptions/OverwriteException.02'); }
}
?>