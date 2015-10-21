<?php

namespace Analyzer\Php;

use Analyzer;

class NoListWithString extends Analyzer\Analyzer {
    protected $phpVersion = '7.0-';
    
    public function analyze() {
        // list($a, $b) = 'string';
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_LIST')
             ->fullnspath('\\list')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs(array('String', 'Concatenation', 'Heredoc'))
             ->back('first');
        $this->prepareQuery();

        // $c = 'string'; list($a, $b) = $c;
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_LIST')
             ->fullnspath('\\list')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'name')
             ->inIs('RIGHT')
             ->previousSibling()
             ->atomIs('Assignation')
             ->code(array('=', '.='))
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'name')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs(array('String', 'Concatenation', 'Heredoc'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
