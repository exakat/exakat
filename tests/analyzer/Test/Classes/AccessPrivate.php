<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class AccessPrivate extends Analyzer {
    /* 4 methods */

    public function testClasses_AccessPrivate01()  { $this->generic_test('Classes_AccessPrivate.01'); }
    public function testClasses_AccessPrivate02()  { $this->generic_test('Classes_AccessPrivate.02'); }
    public function testClasses_AccessPrivate03()  { $this->generic_test('Classes_AccessPrivate.03'); }
    public function testClasses_AccessPrivate04()  { $this->generic_test('Classes/AccessPrivate.04'); }
}
?>