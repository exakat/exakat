<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class AvoidSetErrorHandlerContextArg extends Analyzer {
    /* 3 methods */

    public function testPhp_AvoidSetErrorHandlerContextArg01()  { $this->generic_test('Php/AvoidSetErrorHandlerContextArg.01'); }
    public function testPhp_AvoidSetErrorHandlerContextArg02()  { $this->generic_test('Php/AvoidSetErrorHandlerContextArg.02'); }
    public function testPhp_AvoidSetErrorHandlerContextArg03()  { $this->generic_test('Php/AvoidSetErrorHandlerContextArg.03'); }
}
?>