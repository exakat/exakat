<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_UseCookies extends Analyzer {
    /* 2 methods */

    public function testPhp_UseCookies01()  { $this->generic_test('Php/UseCookies.01'); }
    public function testPhp_UseCookies02()  { $this->generic_test('PHp/UseCookies.02'); }
}
?>