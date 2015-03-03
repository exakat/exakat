<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Analyzer\Files;

use Analyzer;

class DefinitionsOnly extends Analyzer\Analyzer {
    public static $definitions = array('Interface', 'Trait', 'Function', 'Const', 'Class');
    public static $definitionsHelpers = array('Use', 'Global', 'Include');
    //'Namespace',  is excluded

    public static $definitionsFunctions = array('define', 'set_session_handler', 'set_error_handler', 'ini_set', 
                                                'register_shutdown_function');
    
    public function dependsOn() {
        return array('Structures/NoDirectAccess');
    }
    
    public function analyze() {
        $definitionsList = '"'.implode('", "', self::$definitions).'"';
        $nonDefinitionsList = '"'.implode('", "', array_merge(self::$definitions, self::$definitionsHelpers)).'"';

        $definitionsFunctionsList = '"\\\\'.implode('", "\\\\', self::$definitionsFunctions).'"';
        
        $definitions = 'it.atom in ['.$definitionsList.', "Namespace"]  || (it.atom == "Functioncall" && it.fullnspath in ['.$definitionsFunctionsList.']) || it.in("ANALYZED").has("code", "Analyzer\\\\Structures\\\\NoDirectAccess").any()';
        $nonDefinitions = 'it.atom in ['.$definitionsList.', '.$nonDefinitionsList.', "Namespace"]  || (it.atom == "Functioncall" && it.fullnspath in ['.$definitionsFunctionsList.']) || it.in("ANALYZED").has("code", "Analyzer\\\\Structures\\\\NoDirectAccess").any()';

        // all cases without extra string before/after the script
        
        // one or several namespaces
        $this->atomIs('File')
             ->outIs('FILE')
             ->atomIs('Phpcode')
             ->outIs('CODE')

             // spot a definition
             ->raw('filter{ it.out("ELEMENT").has("atom", "Namespace").out("BLOCK").out("ELEMENT").filter{ '.$definitions.' }.any()}')

             // spot a non-definition
             ->raw('filter{ it.out("ELEMENT").has("atom", "Namespace").out("BLOCK").out("ELEMENT").filter{ !('.$nonDefinitions.')}.any() == false}')

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
             ->raw('filter{ it.out("ELEMENT").filter{ !('.$nonDefinitions.')}.any() == false}')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
