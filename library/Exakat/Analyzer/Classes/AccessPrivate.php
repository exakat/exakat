<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
    public function analyze() {
        $hasPrivateMethodDefinition = 'where( __.out("METHOD").hasLabel("Method")
                                                .out("NAME").filter{it.get().value("code") == name}.in("NAME")
                                                .has("visibility", "private") )';
        $notHasPrivateMethodDefinition = 'not( where( __.out("METHOD").hasLabel("Method")
                                                        .out("NAME").filter{it.get().value("code") == name}.in("NAME")
                                                        .has("visibility", "private") ) 
                                             ) ';

        $hasPrivateProperty = 'where( __.out("PPP").hasLabel("Ppp").has("visibility", "private")
                                        .where( __.out("PPP")
                                                  .coalesce(out("LEFT"),  __.filter{true} )
                                                  .filter{it.get().value("code") == name} ) 
                                    )';
        
        // methods
        // classname::method() direct class
        // classname::method() parent class through extension (not the direct class)
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->has('fullnspath')
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->isNotLocalClass()
             ->classDefinition()
             ->goToAllParents(self::INCLUDE_SELF)
             ->raw($hasPrivateMethodDefinition)
             ->back('first');
        $this->prepareQuery();

        // Case of parent::
        $this->atomIs('Staticmethodcall')
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
             ->goToAllParents()
             ->raw($hasPrivateMethodDefinition)
             ->back('first');
        $this->prepareQuery();

        // properties
        // className::$property direct call
        $this->atomIs('Staticproperty')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->inIs('MEMBER')
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->isNotLocalClass()

             ->goToClass()
             ->raw($hasPrivateProperty)

             ->back('first');
        $this->prepareQuery();

        // parent::$property
        $this->atomIs('Staticproperty')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->inIs('MEMBER')
             ->outIs('CLASS')
             ->atomIs('Parent')
             ->isNotLocalClass()

             ->goToClass()
             ->goToAllParents(self::EXCLUDE_SELF)
             ->raw($hasPrivateProperty)

             ->back('first');
        $this->prepareQuery();
    }
}

?>
