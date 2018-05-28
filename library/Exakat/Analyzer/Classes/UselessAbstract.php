<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UselessAbstract extends Analyzer {
    public function dependsOn() {
        return array('Classes/OnlyStaticMethods',
                    );
    }
    
    public function analyze() {
        // abstract class that are never used
        $this->atomIs('Class')
             ->hasOut('ABSTRACT')
             ->analyzerIsNot('Classes/OnlyStaticMethods')
             ->hasNoOut('DEFINITION')
             ->back('first');
        $this->prepareQuery();

        // abstract class without nothing in
        $this->atomIs('Class')
             ->hasOut('ABSTRACT')
             ->hasOut('DEFINITION')
             ->hasNoOut(array('METHOD', 'MAGICMETHOD', 'USE', 'CONST', 'PPP'));
        $this->prepareQuery();

        // abstract class with not methods nor const nor trait
        $this->atomIs('Class')
             ->hasOut('ABSTRACT')
             ->hasOut('DEFINITION')
             ->raw('not( where( __.out("METHOD", "MAGICMETHOD", "USE", "PPP", "CONST").hasLabel("Method", "Magicmethod", "Usetrait", "Ppp", "Const") ) )');
        $this->prepareQuery();

        // abstract class with not abstract methods
        $this->atomIs('Class')
             ->hasOut('ABSTRACT')
             ->hasOut('DEFINITION')
             ->hasOut(array('METHOD', 'MAGICMETHOD'))
             ->raw('not( where( __.out("METHOD", "MAGICMETHOD").where( __.out("ABSTRACT"))) )')
             ->raw('not( where( __.out("USE").out("USE").in("DEFINITION").out("METHOD", "MAGICMETHOD").where( __.out("ABSTRACT"))) )');
        $this->prepareQuery();
     }
}

?>
