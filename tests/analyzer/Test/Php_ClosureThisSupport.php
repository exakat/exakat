<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_ClosureThisSupport extends Analyzer {
    /* 2 methods */

    public function testPhp_ClosureThisSupport01()  { $this->generic_test('Php_ClosureThisSupport.01'); }
    public function testPhp_ClosureThisSupport02()  { $this->generic_test('Php_ClosureThisSupport.02'); }
}
?>