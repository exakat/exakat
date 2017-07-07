<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_DeclareStrict extends Analyzer {
    /* 3 methods */

    public function testPhp_DeclareStrict01()  { $this->generic_test('Php/DeclareStrict.01'); }
    public function testPhp_DeclareStrict02()  { $this->generic_test('Php/DeclareStrict.02'); }
    public function testPhp_DeclareStrict03()  { $this->generic_test('Php/DeclareStrict.03'); }
}
?>