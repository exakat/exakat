<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_ListWithKeys extends Analyzer {
    /* 3 methods */

    public function testPhp_ListWithKeys01()  { $this->generic_test('Php/ListWithKeys.01'); }
    public function testPhp_ListWithKeys02()  { $this->generic_test('Php/ListWithKeys.02'); }
    public function testPhp_ListWithKeys03()  { $this->generic_test('Php/ListWithKeys.03'); }
}
?>