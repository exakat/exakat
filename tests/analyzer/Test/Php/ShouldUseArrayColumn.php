<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_ShouldUseArrayColumn extends Analyzer {
    /* 4 methods */

    public function testPhp_ShouldUseArrayColumn01()  { $this->generic_test('Php/ShouldUseArrayColumn.01'); }
    public function testPhp_ShouldUseArrayColumn02()  { $this->generic_test('Php/ShouldUseArrayColumn.02'); }
    public function testPhp_ShouldUseArrayColumn03()  { $this->generic_test('Php/ShouldUseArrayColumn.03'); }
    public function testPhp_ShouldUseArrayColumn04()  { $this->generic_test('Php/ShouldUseArrayColumn.04'); }
}
?>