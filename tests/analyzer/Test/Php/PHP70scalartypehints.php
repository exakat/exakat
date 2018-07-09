<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_PHP70scalartypehints extends Analyzer {
    /* 2 methods */

    public function testPhp_PHP70scalartypehints01()  { $this->generic_test('Php/PHP70scalartypehints.01'); }
    public function testPhp_PHP70scalartypehints02()  { $this->generic_test('Php/PHP70scalartypehints.02'); }
}
?>