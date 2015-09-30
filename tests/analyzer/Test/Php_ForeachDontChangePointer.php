<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_ForeachDontChangePointer extends Analyzer {
    /* 2 methods */

    public function testPhp_ForeachDontChangePointer01()  { $this->generic_test('Php_ForeachDontChangePointer.01'); }
    public function testPhp_ForeachDontChangePointer02()  { $this->generic_test('Php_ForeachDontChangePointer.02'); }
}
?>