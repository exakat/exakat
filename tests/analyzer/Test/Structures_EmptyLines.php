<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_EmptyLines extends Analyzer {
    /* 4 methods */

    public function testStructures_EmptyLines01()  { $this->generic_test('Structures_EmptyLines.01'); }
    public function testStructures_EmptyLines02()  { $this->generic_test('Structures_EmptyLines.02'); }
    public function testStructures_EmptyLines03()  { $this->generic_test('Structures_EmptyLines.03'); }
    public function testStructures_EmptyLines04()  { $this->generic_test('Structures_EmptyLines.04'); }
}
?>