<?php
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


namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class Php7IndirectExpression extends Analyzer {
    protected $phpVersion = '7.0+';
    
    public function analyze() {
//$$foo['bar']['baz']	${$foo['bar']['baz']}	($$foo)['bar']['baz']
        $this->atomIs('Variable')
             ->tokenIs('T_DOLLAR')
             ->outIs('NAME')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs('Array')
             ->back('first');
        $this->prepareQuery();

//$foo->$bar['baz']	$foo->{$bar['baz']}	($foo->$bar)['baz']
        $this->atomIs('Array')
             ->hasNoIn('NAME')
             ->outIs('VARIABLE')
             ->atomIs('Member')
             ->outIs('MEMBER')
             ->atomIs('Variable')
             ->back('first');
        $this->prepareQuery();

//Foo::$bar['baz']();
        $this->atomIs('Functioncall')
             ->outIs('NAME')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs(array('Member', 'Staticproperty'))
             ->outIs('MEMBER')
             ->atomIs(array('Variable', 'Staticpropertyname'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
