<?php

namespace Analyzer\Structures;

use Analyzer;

class NoHardcodedPort extends Analyzer\Analyzer {
    public function analyze() {
        $functions = $this->loadIni('php_argument_port.ini');

        $positions = array(0, 1, 2, 3, 4, 5);
        foreach($positions as $position) {
            $this->atomFunctionIs($functions["functions$position"])
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs(array('Integer', 'String'))
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
