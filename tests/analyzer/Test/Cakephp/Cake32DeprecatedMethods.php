<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Cake32DeprecatedMethods extends Analyzer {
    /* 1 methods */

    public function testCakephp_Cake32DeprecatedMethods01()  { $this->generic_test('Cakephp/Cake32DeprecatedMethods.01'); }
}
?>