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

class AvoidUsing extends Analyzer\Analyzer {
    public function analyze() {
        $classes = $this->config;
        
        if (empty($classes)) {
            return null;
        }
        $classes = $this->makeFullNsPath($classes);

        // class may be used in a class
        $this->atomIs('Class')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();
        
        // class may be used in a new
        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Staticmethodcall
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Staticproperty
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Staticconstant
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Instanceof
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Typehint
        $this->atomIs('Typehint')
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in an extension
        $this->atomIs('Class')
             ->outIs(array('EXTENDS', 'IMPLEMENTS'))
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in an use
        $this->atomIs('Use')
             ->outIs('USE')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\class_alias')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0);
        $this->prepareQuery();

        // mentions in strings
        $this->atomIs('String')
             ->noDelimiter($this->config);
        $this->prepareQuery();

    }
}

?>
