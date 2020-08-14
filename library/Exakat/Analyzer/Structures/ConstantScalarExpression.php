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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ConstantScalarExpression extends Analyzer {
    protected $phpVersion = '5.6+';

    public function analyze(): void {
        $authorizedAtoms = array('Integer', 'String', 'Float', 'Boolean', 'Void', 'Staticconstant', 'Null', 'Identifier');

        // in constants
        $this->atomIs('Const')
             ->outIs('CONST')
             ->outIs('VALUE')
             ->atomIsNot($authorizedAtoms)
             ->back('first');
        $this->prepareQuery();

        // in argument's default value
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('DEFAULT')
             ->hasNoIn('RIGHT')
             ->atomIsNot($authorizedAtoms)
             ->back('first');
        $this->prepareQuery();

        // in property's default value
        $this->atomIs(array('Class', 'Trait'))
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->outIs('DEFAULT')
             ->hasNoIn('RIGHT')
             ->atomIsNot($authorizedAtoms)
             ->inIs('DEFAULT');
        $this->prepareQuery();
    }
}

?>
