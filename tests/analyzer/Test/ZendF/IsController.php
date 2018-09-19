<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class IsController extends Analyzer {
    /* 2 methods */

    public function testZendF_IsController01()  { $this->generic_test('ZendF/IsController.01'); }
    public function testZendF_IsController02()  { $this->generic_test('ZendF/IsController.02'); }
}
?>