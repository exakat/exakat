<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Anonymous extends Analyzer {
    /* 3 methods */

    public function testClasses_Anonymous01()  { $this->generic_test('Classes_Anonymous.01'); }
    public function testClasses_Anonymous02()  { $this->generic_test('Classes_Anonymous.02'); }
    public function testClasses_Anonymous03()  { $this->generic_test('Classes_Anonymous.03'); }
}
?>