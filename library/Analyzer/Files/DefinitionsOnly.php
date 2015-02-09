<?php

namespace Analyzer\Files;

use Analyzer;

class DefinitionsOnly extends Analyzer\Analyzer {
    public static $definitions = array('Interface', 'Trait', 'Function', 'Const', 'Class', 'Use', 'Global', 'Include');
    //'Namespace',  is excluded

    public static $definitionsFunctions = array('define', 'set_session_handler', 'set_error_handler', 'ini_set');
    
    public function dependsOn() {
        return array('Structures/NoDirectAccess');
    }
    
    public function analyze() {
        $definitionsList = '"'.implode('", "', self::$definitions).'"';
        $definitionsFunctionsList = '"\\\\'.implode('", "\\\\', self::$definitionsFunctions).'"';
        
        $definitions = 'it.atom in ['.$definitionsList.', "Namespace"]  || (it.atom == "Functioncall" && it.fullnspath in ['.$definitionsFunctionsList.']) || it.in("ANALYZED").has("code", "Analyzer\\\\Structures\\\\NoDirectAccess").any()';

        // all cases without extra string before/after the script
        
        // one or several namespaces
        $this->atomIs('File')
             ->outIs('FILE')
             ->atomIs('Phpcode')
             ->outIs('CODE')

             // spot a definition
             ->raw('filter{ it.out("ELEMENT").has("atom", "Namespace").out("BLOCK").out("ELEMENT").filter{ '.$definitions.' }.any()}')

             // spot a non-definition
             ->raw('filter{ it.out("ELEMENT").has("atom", "Namespace").out("BLOCK").out("ELEMENT").filter{ !('.$definitions.')}.any() == false}')

             ->back('first');
        $this->prepareQuery();

        // namespaces are implicit
        $this->atomIs('File')
             ->outIs('FILE')
             ->atomIs('Phpcode')
             ->outIs('CODE')

             // check that there are no namespaces
             ->raw('filter{ it.out("ELEMENT").has("atom", "Namespace").any() == false}')

             // spot a definition
             ->raw('filter{ it.out("ELEMENT").filter{ '.$definitions.' }.any()}')

             // cannot spot a non-definition
             ->raw('filter{ it.out("ELEMENT").filter{ !('.$definitions.')}.any() == false}')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
