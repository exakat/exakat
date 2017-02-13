<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Cakephp_Cake30DeprecatedClass extends Analyzer {
    /* 2 methods */

    public function testCakephp_Cake30DeprecatedClass01()  { $this->generic_test('Cakephp/Cake30DeprecatedClass.01'); }
    public function testCakephp_Cake30DeprecatedClass02()  { $this->generic_test('Cakephp/Cake30DeprecatedClass.02'); }
}
?>