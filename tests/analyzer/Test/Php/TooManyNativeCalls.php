<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_TooManyNativeCalls extends Analyzer {
    /* 2 methods */

    public function testPhp_TooManyNativeCalls01()  { $this->generic_test('Php/TooManyNativeCalls.01'); }
    public function testPhp_TooManyNativeCalls02()  { $this->generic_test('Php/TooManyNativeCalls.02'); }
}
?>