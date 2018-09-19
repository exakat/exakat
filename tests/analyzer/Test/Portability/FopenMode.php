<?php

namespace Test\Portability;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class FopenMode extends Analyzer {
    /* 2 methods */

    public function testPortability_FopenMode01()  { $this->generic_test('Portability_FopenMode.01'); }
    public function testPortability_FopenMode02()  { $this->generic_test('Portability_FopenMode.02'); }
}
?>