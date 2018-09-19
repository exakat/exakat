<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NoPublicAccess extends Analyzer {
    /* 3 methods */

    public function testClasses_NoPublicAccess01()  { $this->generic_test('Classes_NoPublicAccess.01'); }
    public function testClasses_NoPublicAccess02()  { $this->generic_test('Classes_NoPublicAccess.02'); }
    public function testClasses_NoPublicAccess03()  { $this->generic_test('Classes/NoPublicAccess.03'); }
}
?>