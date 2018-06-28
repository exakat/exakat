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

class DefinedParentMP extends Analyzer {
    public function dependsOn() {
        return array('Composer/IsComposerNsname',
                     'Classes/IsExtClass',
                    );
    }
    
    public function analyze() {
        $isComposerClass = 'where( __.out("EXTENDS").in("ANALYZED").has("analyzer", "Composer/IsComposerNsname") )';
        $isPhpClass = 'where( __.out("EXTENDS").in("ANALYZED").has("analyzer", "Classes/IsExtClass") )';
        
        // parent::method()
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->goToClass()
             ->goToAllParents(self::EXCLUDE_SELF)
             ->outIs('METHOD')
             ->atomIs('Method')
             ->isNot('visibility', 'private')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // handle composer case
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->goToClass()
             ->raw($isComposerClass)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->goToClass()
             ->goToAllParents(self::EXCLUDE_SELF)
             ->raw($isComposerClass)
             ->back('first');
        $this->prepareQuery();

        // Case of PHP class
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->goToClass()
             ->goToAllParents(self::INCLUDE_SELF)
             ->raw($isPhpClass)
             ->back('first');
        $this->prepareQuery();
        
        // parent::$property
        $this->atomIs('Staticproperty')
             ->outIs('MEMBER')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'name')
             ->inIs('MEMBER')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->goToClass()
             ->goToAllParents(self::EXCLUDE_SELF)
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->isNot('visibility', 'private')
             ->outIs('PPP')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // handle composer case
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->goToClass()
             ->goToAllParents(self::INCLUDE_SELF)
             ->raw($isComposerClass)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticproperty')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->inIs('MEMBER')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->goToClass()
             ->raw($isPhpClass)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticproperty')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->inIs('MEMBER')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->goToClass()
             ->goToAllParents()
             ->raw($isPhpClass)
             ->back('first');
        $this->prepareQuery();
        
        // defined in traits (via use)
        $this->atomIs('Member')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToTraits()
             ->outIs('METHOD')
             ->atomIs('Ppp')
             ->outIs('PPP')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
