<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Cakephp_Cake33DeprecatedTraits extends Analyzer {
    /* 2 methods */

    public function testCakephp_Cake33DeprecatedTraits01()  { $this->generic_test('Cakephp/Cake33DeprecatedTraits.01'); }
    public function testCakephp_Cake33DeprecatedTraits02()  { $this->generic_test('Cakephp/Cake33DeprecatedTraits.02'); }
}
?>