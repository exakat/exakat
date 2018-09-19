<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class MakeDefault extends Analyzer {
    /* 3 methods */

    public function testClasses_MakeDefault01()  { $this->generic_test('Classes_MakeDefault.01'); }
    public function testClasses_MakeDefault02()  { $this->generic_test('Classes_MakeDefault.02'); }
    public function testClasses_MakeDefault03()  { $this->generic_test('Classes/MakeDefault.03'); }
}
?>