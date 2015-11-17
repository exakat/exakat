<?php

namespace Analyzer\Structures;

use Analyzer;

class ErrorMessages extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Exceptions/DefinedExceptions');
    }
    
    public function analyze() {
        $messages = array('String', 'Concatenation', 'Integer', 'Functioncall');

        // die('true')
        // exit ('30');
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_DIE', 'T_EXIT'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs($messages);
        $this->prepareQuery();

        //  new \Exception('Message');
        $this->atomIs('New')
             ->outIs('NEW')
             ->atomIs('Functioncall')
             ->isNot('fullnspath', null)
             ->fullnspath('\\exception')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs($messages);
        $this->prepareQuery();

        //  new $exception('Message');
        $this->atomIs('Throw')
             ->outIs('THROW')
             ->atomIs('New')
             ->outIs('NEW')
             ->atomIsNot('Functioncall')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs($messages);
        $this->prepareQuery();

        //  new myException('Message');
        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->isNot('fullnspath', null)
             ->filter(' g.idx("classes")[["path":it.fullnspath]].in("ANALYZED").has("code","Analyzer\\\\Exceptions\\\\DefinedExceptions").any()')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs($messages);
        $this->prepareQuery();
    }
}

?>
