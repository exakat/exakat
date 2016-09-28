<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_SetExceptionHandlerPHP7 extends Analyzer {
    /* 2 methods */

    public function testPhp_SetExceptionHandlerPHP701()  { $this->generic_test('Php/SetExceptionHandlerPHP7.01'); }
    public function testPhp_SetExceptionHandlerPHP702()  { $this->generic_test('Php/SetExceptionHandlerPHP7.02'); }
}
?>