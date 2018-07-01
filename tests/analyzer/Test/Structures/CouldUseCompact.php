<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_CouldUseCompact extends Analyzer {
    /* 6 methods */

    public function testStructures_CouldUseCompact01()  { $this->generic_test('Structures/CouldUseCompact.01'); }
    public function testStructures_CouldUseCompact02()  { $this->generic_test('Structures/CouldUseCompact.02'); }
    public function testStructures_CouldUseCompact03()  { $this->generic_test('Structures/CouldUseCompact.03'); }
    public function testStructures_CouldUseCompact04()  { $this->generic_test('Structures/CouldUseCompact.04'); }
    public function testStructures_CouldUseCompact05()  { $this->generic_test('Structures/CouldUseCompact.05'); }
    public function testStructures_CouldUseCompact06()  { $this->generic_test('Structures/CouldUseCompact.06'); }
}
?>