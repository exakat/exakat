<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_UsePathinfoArgs extends Analyzer {
    /* 3 methods */

    public function testPhp_UsePathinfoArgs01()  { $this->generic_test('Php/UsePathinfoArgs.01'); }
    public function testPhp_UsePathinfoArgs02()  { $this->generic_test('Php/UsePathinfoArgs.02'); }
    public function testPhp_UsePathinfoArgs03()  { $this->generic_test('Php/UsePathinfoArgs.03'); }
}
?>