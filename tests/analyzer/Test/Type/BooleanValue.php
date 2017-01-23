<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Type_BooleanValue extends Analyzer {
    /* 2 methods */

    public function testType_BooleanValue01()  { $this->generic_test('Type/BooleanValue.01'); }
    public function testType_BooleanValue02()  { $this->generic_test('Type/BooleanValue.02'); }
}
?>