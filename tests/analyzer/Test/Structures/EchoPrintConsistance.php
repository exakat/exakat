<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_EchoPrintConsistance extends Analyzer {
    /* 4 methods */

    public function testStructures_EchoPrintConsistance01()  { $this->generic_test('Structures_EchoPrintConsistance.01'); }
    public function testStructures_EchoPrintConsistance02()  { $this->generic_test('Structures_EchoPrintConsistance.02'); }
    public function testStructures_EchoPrintConsistance03()  { $this->generic_test('Structures/EchoPrintConsistance.03'); }
    public function testStructures_EchoPrintConsistance04()  { $this->generic_test('Structures/EchoPrintConsistance.04'); }
}
?>