<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_CouldUseCompact extends Analyzer {
    /* 3 methods */

    public function testStructures_CouldUseCompact01()  { $this->generic_test('Structures/CouldUseCompact.01'); }
    public function testStructures_CouldUseCompact02()  { $this->generic_test('Structures/CouldUseCompact.02'); }
    public function testStructures_CouldUseCompact03()  { $this->generic_test('Structures/CouldUseCompact.03'); }
}
?>