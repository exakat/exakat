<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_IncludeUsage extends Analyzer {
    /* 3 methods */

    public function testStructures_IncludeUsage01()  { $this->generic_test('Structures_IncludeUsage.01'); }
    public function testStructures_IncludeUsage02()  { $this->generic_test('Structures/IncludeUsage.02'); }
    public function testStructures_IncludeUsage03()  { $this->generic_test('Structures/IncludeUsage.03'); }
}
?>