<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_GtOrLtFavorite extends Analyzer {
    /* 2 methods */

    public function testStructures_GtOrLtFavorite01()  { $this->generic_test('Structures/GtOrLtFavorite.01'); }
    public function testStructures_GtOrLtFavorite02()  { $this->generic_test('Structures/GtOrLtFavorite.02'); }
}
?>