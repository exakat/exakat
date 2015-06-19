<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_ReturnTypehintUsage extends Analyzer {
    /* 4 methods */

    public function testPhp_ReturnTypehintUsage01()  { $this->generic_test('Php_ReturnTypehintUsage.01'); }
    public function testPhp_ReturnTypehintUsage02()  { $this->generic_test('Php_ReturnTypehintUsage.02'); }
    public function testPhp_ReturnTypehintUsage03()  { $this->generic_test('Php_ReturnTypehintUsage.03'); }
    public function testPhp_ReturnTypehintUsage04()  { $this->generic_test('Php_ReturnTypehintUsage.04'); }
}
?>