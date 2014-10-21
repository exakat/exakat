<?php

namespace Analyzer\Structures;

use Analyzer;

class UnreachableCode extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Return")
             ->nextSibling();
        $this->prepareQuery();

        $this->atomIs("Throw")
             ->nextSibling();
        $this->prepareQuery();

        $this->atomIs("Break")
             ->nextSibling();
        $this->prepareQuery();

        $this->atomIs("Continue")
             ->nextSibling();
        $this->prepareQuery();

        $this->atomIs("Goto")
             ->nextSibling()
             ->atomIsNot('Label');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR', 'T_EXIT', 'T_DIE'))
             ->fullnspath(array('\\exit', '\\die'))
             ->nextSibling();
        $this->prepareQuery();
    }
}

?>