<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Sql extends Analyzer {
    /* 2 methods */

    public function testType_Sql01()  { $this->generic_test('Type/Sql.01'); }
    public function testType_Sql02()  { $this->generic_test('Type/Sql.02'); }
}
?>