<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ShouldTypecast extends Analyzer {
    /* 1 methods */

    public function testType_ShouldTypecast01()  { $this->generic_test('Type_ShouldTypecast.01'); }
}
?>