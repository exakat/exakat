<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Type_GPCIndex extends Analyzer {
    /* 1 methods */

    public function testType_GPCIndex01()  { $this->generic_test('Type/GPCIndex.01'); }
}
?>