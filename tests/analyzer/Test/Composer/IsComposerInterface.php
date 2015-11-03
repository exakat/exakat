<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Composer_IsComposerInterface extends Analyzer {
    /* 2 methods */

    public function testComposer_IsComposerInterface01()  { $this->generic_test('Composer_IsComposerInterface.01'); }
    public function testComposer_IsComposerInterface02()  { $this->generic_test('Composer_IsComposerInterface.02'); }
}
?>