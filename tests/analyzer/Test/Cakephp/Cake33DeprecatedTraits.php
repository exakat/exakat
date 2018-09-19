<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Cake33DeprecatedTraits extends Analyzer {
    /* 2 methods */

    public function testCakephp_Cake33DeprecatedTraits01()  { $this->generic_test('Cakephp/Cake33DeprecatedTraits.01'); }
    public function testCakephp_Cake33DeprecatedTraits02()  { $this->generic_test('Cakephp/Cake33DeprecatedTraits.02'); }
}
?>