<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_Php56NewFunctions extends Analyzer {
    /* 2 methods */

    public function testPhp_Php56NewFunctions01()  { $this->generic_test('Php_Php56NewFunctions.01'); }
    public function testPhp_Php56NewFunctions02()  { $this->generic_test('Php_Php56NewFunctions.02'); }
}
?>