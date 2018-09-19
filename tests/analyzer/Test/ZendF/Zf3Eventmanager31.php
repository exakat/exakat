<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Zf3Eventmanager31 extends Analyzer {
    /* 2 methods */

    public function testZendF_Zf3Eventmanager3101()  { $this->generic_test('ZendF/Zf3Eventmanager31.01'); }
    public function testZendF_Zf3Eventmanager3102()  { $this->generic_test('ZendF/Zf3Eventmanager31.02'); }
}
?>