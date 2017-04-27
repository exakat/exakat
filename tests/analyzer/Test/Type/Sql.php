<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Type_Sql extends Analyzer {
    /* 2 methods */

    public function testType_Sql01()  { $this->generic_test('Type/Sql.01'); }
    public function testType_Sql02()  { $this->generic_test('Type/Sql.02'); }
}
?>