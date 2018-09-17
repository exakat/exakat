<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_MissingNew extends Analyzer {
    /* 3 methods */

    public function testStructures_MissingNew01()  { $this->generic_test('Structures/MissingNew.01'); }
    public function testStructures_MissingNew02()  { $this->generic_test('Structures/MissingNew.02'); }
    public function testStructures_MissingNew03()  { $this->generic_test('Structures/MissingNew.03'); }
}
?>