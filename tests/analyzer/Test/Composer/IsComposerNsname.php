<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Composer_IsComposerNsname extends Analyzer {
    /* 2 methods */

    public function testComposer_IsComposerNsname01()  { $this->generic_test('Composer/IsComposerNsname.01'); }
    public function testComposer_IsComposerNsname02()  { $this->generic_test('Composer/IsComposerNsname.02'); }
}
?>