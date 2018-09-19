<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UndefinedConstants extends Analyzer {
    /* 7 methods */

    public function testClasses_UndefinedConstants01()  { $this->generic_test('Classes_UndefinedConstants.01'); }
    public function testClasses_UndefinedConstants02()  { $this->generic_test('Classes_UndefinedConstants.02'); }
    public function testClasses_UndefinedConstants03()  { $this->generic_test('Classes_UndefinedConstants.03'); }
    public function testClasses_UndefinedConstants04()  { $this->generic_test('Classes_UndefinedConstants.04'); }
    public function testClasses_UndefinedConstants05()  { $this->generic_test('Classes/UndefinedConstants.05'); }
    public function testClasses_UndefinedConstants06()  { $this->generic_test('Classes/UndefinedConstants.06'); }
    public function testClasses_UndefinedConstants07()  { $this->generic_test('Classes/UndefinedConstants.07'); }
}
?>