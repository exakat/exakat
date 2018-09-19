<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Cakephp32 extends Analyzer {
    /* 1 methods */

    public function testCakephp_Cakephp3201()  { $this->generic_test('Cakephp/Cakephp32.01'); }
}
?>