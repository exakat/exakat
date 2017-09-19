<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_PathinfoReturns extends Analyzer {
    /* 2 methods */

    public function testPhp_PathinfoReturns01()  { $this->generic_test('Php/PathinfoReturns.01'); }
    public function testPhp_PathinfoReturns02()  { $this->generic_test('Php/PathinfoReturns.02'); }
}
?>