<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_ListWithAppends extends Analyzer {
    /* 3 methods */

    public function testPhp_ListWithAppends01()  { $this->generic_test('Php_ListWithAppends.01'); }
    public function testPhp_ListWithAppends02()  { $this->generic_test('Php/ListWithAppends.02'); }
    public function testPhp_ListWithAppends03()  { $this->generic_test('Php/ListWithAppends.03'); }
}
?>