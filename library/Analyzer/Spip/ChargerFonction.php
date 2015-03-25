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
