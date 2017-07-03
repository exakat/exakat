<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_ConstDefineFavorite extends Analyzer {
    /* 2 methods */

    public function testStructures_ConstDefineFavorite01()  { $this->generic_test('Structures/ConstDefineFavorite.01'); }
    public function testStructures_ConstDefineFavorite02()  { $this->generic_test('Structures/ConstDefineFavorite.02'); }
}
?>