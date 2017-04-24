<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_QueriesInLoop extends Analyzer {
    /* 3 methods */

    public function testStructures_QueriesInLoop01()  { $this->generic_test('Structures_QueriesInLoop.01'); }
    public function testStructures_QueriesInLoop02()  { $this->generic_test('Structures/QueriesInLoop.02'); }
    public function testStructures_QueriesInLoop03()  { $this->generic_test('Structures/QueriesInLoop.03'); }
}
?>