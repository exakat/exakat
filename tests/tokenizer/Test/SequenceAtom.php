<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class SequenceAtom extends Tokenizer {
    /* 2 methods */

    public function testSequenceAtom01()  { $this->generic_test('SequenceAtom.01'); }
}
?>