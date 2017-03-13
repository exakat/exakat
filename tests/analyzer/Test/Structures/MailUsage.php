<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_MailUsage extends Analyzer {
    /* 3 methods */

    public function testStructures_MailUsage01()  { $this->generic_test('Structures_MailUsage.01'); }
    public function testStructures_MailUsage02()  { $this->generic_test('Structures/MailUsage.02'); }
    public function testStructures_MailUsage03()  { $this->generic_test('Structures/MailUsage.03'); }
}
?>