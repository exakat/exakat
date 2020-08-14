<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class AccessPrivate extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/SetParentDefinition',
                     'Complete/MakeClassMethodDefinition',
                     'Complete/SolveTraitMethods',
                     'Complete/SetClassMethodRemoteDefinition',
                    );
    }

    public function analyze(): void {
        $hasPrivateMethodDefinition = 'where( __.out("METHOD").hasLabel("Method")
                                                .out("NAME").filter{it.get().value("code") == name}.in("NAME")
                                                .has("visibility", "private") )';
        $notHasPrivateMethodDefinition = 'not( where( __.out("METHOD").hasLabel("Method")
                                                        .out("NAME").filter{it.get().value("code") == name}.in("NAME")
                                                        .has("visibility", "private") ) 
                                             ) ';

        // methods
        // classname::method() direct class
        // classname::method() parent class through extension (not the direct class)
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->has('fullnspath')
             ->atomIsNot(self::RELATIVE_CLASS)
             ->isNotLocalClass()
             ->classDefinition()
             ->goToAllParents(self::INCLUDE_SELF)
             ->raw($hasPrivateMethodDefinition)
             ->back('first');
        $this->prepareQuery();

        // Case of parent::
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->atomIs('Parent')
             ->isNotLocalClass()
             ->goToClass()
             ->goToAllParents(self::EXCLUDE_SELF)
             ->raw($hasPrivateMethodDefinition)
             ->back('first');
        $this->prepareQuery();

        // self / static::method() in parent class
        // static : the class which is called
        // self   : the class where the definition is
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->atomIs(array('Self', 'Static'))
             ->has('fullnspath')
             ->goToClass()
             // no local method
             ->raw($notHasPrivateMethodDefinition)
             ->goToAllParents(self::EXCLUDE_SELF)
             ->raw($hasPrivateMethodDefinition)
             ->back('first');
        $this->prepareQuery();

        // parent::$p
        $this->atomIs('Staticproperty')
             ->analyzerIsNot('self')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->inIs('MEMBER')
             ->outIs('CLASS')
             ->atomis('Parent')

             ->inIs('CLASS')

             ->inIs('DEFINITION')
             ->inIs('PPP')
             ->is('visibility', 'private')

             ->back('first');
        $this->prepareQuery();

        // properties
        // className::$property direct call
        // C::$property
        $this->atomIs('Staticproperty')
             ->analyzerIsNot('self')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->inIs('MEMBER')
             ->outIs('CLASS')
             ->atomIs(self::STATIC_NAMES)
             ->isNotLocalClass()
             ->inIs('CLASS')

             ->inIs('DEFINITION')
             ->inIs('PPP')
             ->is('visibility', 'private')
             ->back('first');
        $this->prepareQuery();

        // $this->method()
        $this->atomIs('Methodcall')
             ->analyzerIsNot('self')
             ->goToClass()
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->inIs('DEFINITION')
             ->is('visibility', 'private')
             ->inIs('METHOD')
             ->notSamePropertyAs('fullnspath', 'fnp')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
