<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
namespace Exakat\Analyzer\ZendF;

use Exakat\Analyzer\Analyzer;

class ZendClasses extends Analyzer {
    public function dependsOn() {
        return array('Classes/ClassUsage');
    }
    
    public function analyze() {
        $regex = '^\\\\\\\\zend(_|\\\\\\\\)';

        $this->atomIs('New')
             ->outIs('NEW')
             ->has('fullnspath')
             ->regexIs('fullnspath', $regex);
        $this->prepareQuery();
        
        $this->atomIs(array('Staticmethodcall', 'Staticproperty', 'Staticconstant'))
             ->outIs('CLASS')
             ->has('fullnspath')
             ->regexIs('fullnspath', $regex);
        $this->prepareQuery();

        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->regexIs('fullnspath', $regex);
        $this->prepareQuery();

        $this->atomIs('Class')
             ->outIs('EXTENDS')
             ->regexIs('fullnspath', $regex);
        $this->prepareQuery();

// Check that... Const/function and aliases
/*
        $this->atomIs('Use')
             ->outIs('USE')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($classes);
        $this->prepareQuery();
        */
    }
}

?>
