<?php

namespace Analyzer\Spip;

use Analyzer;

class ChargerFonction extends Analyzer\Analyzer {
    public function analyze() {
//-* charger_fonction('toto') -> implique la présence d'une fonction toto_dist()
        $spipFunctions = $this->loadIni('spip/_dist.ini', 'functions');
        
        // two arguments 
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\charger_fonction')
             ->outIs('ARGUMENTS')

             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->savePropertyAs('noDelimiter', 'fonction')
             ->inIs('ARGUMENT')

             ->outIs('ARGUMENT')
             ->is('rank', 1)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->codeIsNot(array('""', "''"))
             ->savePropertyAs('noDelimiter', 'sub')
             
             ->raw('filter{ !(sub.replace("/", "_") + "_" + fonction in ["'.join('", "', $spipFunctions).'"])}')
             ->back('first');
        $this->prepareQuery();

        // one argument (default 'exec')
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\charger_fonction')
             ->outIs('ARGUMENTS')

             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->savePropertyAs('noDelimiter', 'fonction')
             ->inIs('ARGUMENT')
             
             ->noChildWithRank('ARGUMENT', 1)

             ->raw('filter{ !("exec_" + fonction in ["'.join('", "', $spipFunctions).'"])}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
