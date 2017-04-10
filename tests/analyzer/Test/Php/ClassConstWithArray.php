<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_ClassConstWithArray extends Analyzer {
    /* 2 methods */

    public function testPhp_ClassConstWithArray01()  { $this->generic_test('Php/ClassConstWithArray.01'); }
    public function testPhp_ClassConstWithArray02()  { $this->generic_test('Php/ClassConstWithArray.02'); }
}
?>