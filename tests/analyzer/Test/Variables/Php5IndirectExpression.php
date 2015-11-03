<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_Php5IndirectExpression extends Analyzer {
    /* 1 methods */

    public function testVariables_Php5IndirectExpression01()  { $this->generic_test('Variables_Php5IndirectExpression.01'); }
}
?>