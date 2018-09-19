<?php

namespace Test\Melis;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UndefinedConfType extends Analyzer {
    /* 1 methods */

    public function testMelis_UndefinedConfType01()  { $this->generic_test('Melis/UndefinedConfType.01'); }
}
?>