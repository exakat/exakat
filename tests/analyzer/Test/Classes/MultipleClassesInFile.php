<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_MultipleClassesInFile extends Analyzer {
    /* 3 methods */

    public function testClasses_MultipleClassesInFile01()  { $this->generic_test('Classes_MultipleClassesInFile.01'); }
    public function testClasses_MultipleClassesInFile02()  { $this->generic_test('Classes_MultipleClassesInFile.02'); }
    public function testClasses_MultipleClassesInFile03()  { $this->generic_test('Classes_MultipleClassesInFile.03'); }
}
?>