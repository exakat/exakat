<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_CookiesVariables extends Analyzer {
    /* 2 methods */

    public function testPhp_CookiesVariables01()  { $this->generic_test('Php/CookiesVariables.01'); }
    public function testPhp_CookiesVariables02()  { $this->generic_test('Php/CookiesVariables.02'); }
}
?>