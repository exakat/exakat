<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class PearUsage extends Analyzer {
    /* 3 methods */

    public function testPhp_PearUsage01()  { $this->generic_test('Php/PearUsage.01'); }
    public function testPhp_PearUsage02()  { $this->generic_test('Php/PearUsage.02'); }
    public function testPhp_PearUsage03()  { $this->generic_test('Php/PearUsage.03'); }
}
?>