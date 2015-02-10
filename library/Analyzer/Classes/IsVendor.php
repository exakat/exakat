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


namespace Analyzer\Classes;

use Analyzer;

class IsVendor extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Namespaces\\KnownVendor');
    }
    
    public function analyze() {
        // static constants
        // for aliases 
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK', 'CODE')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('first');
        $this->prepareQuery();

        // for direct naming 

        // static methods
        // for aliases 
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK', 'CODE')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('first');
        $this->prepareQuery();

        // for direct naming 

        // static properties
        // for aliases 
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK', 'CODE')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('first');
        $this->prepareQuery();

        // for direct naming 

        // Instanceof
        // for aliases 
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK', 'CODE')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('first');
        $this->prepareQuery();

        // for direct naming 

        // New
        // for aliases with namespaces
        $this->atomIs('New')
             ->outIs('NEW')
             ->_as('classe')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('FILE')
             ->outIsIE('ELEMENT')
             ->outIs('CODE')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('classe');
        $this->prepareQuery();

        // for aliases without namespaces
        $this->atomIs('New')
             ->outIs('NEW')
             ->_as('classe')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('classe');
        $this->prepareQuery();

        // for direct naming 
        $this->atomIs('New')
             ->outIs('NEW')
             ->_as('classe')
             ->tokenIs('T_NS_SEPARATOR')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('classe');
        $this->prepareQuery();

    }
}

?>
