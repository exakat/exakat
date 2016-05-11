<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_Php7IndirectExpression extends Analyzer {
    /* 2 methods */

    public function testVariables_Php7IndirectExpression01()  { $this->generic_test('Variables_Php7IndirectExpression.01'); }
    public function testVariables_Php7IndirectExpression02()  { $this->generic_test('Variables/Php7IndirectExpression.02'); }
}
?>