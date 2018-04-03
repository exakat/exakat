<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_PHP73LastEmptyArgument extends Analyzer {
    /* 2 methods */

    public function testPhp_PHP73LastEmptyArgument01()  { $this->generic_test('Php/PHP73LastEmptyArgument.01'); }
    public function testPhp_PHP73LastEmptyArgument02()  { $this->generic_test('Php/PHP73LastEmptyArgument.02'); }
}
?>