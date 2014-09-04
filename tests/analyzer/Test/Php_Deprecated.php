<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_Deprecated extends Analyzer {
    /* 2 methods */

    public function testPhp_Deprecated01()  { $this->generic_test('Php_Deprecated.01'); }
    public function testPhp_Deprecated02()  { $this->generic_test('Php_Deprecated.02'); }
}
?>