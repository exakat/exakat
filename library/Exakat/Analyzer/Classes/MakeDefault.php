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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class MakeDefault extends Analyzer {
    public function analyze() {
        // class x { private $y; }
        $this->atomIs(self::$CLASSES_ALL)
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->outIs('NAME')
             ->codeIs('__construct')
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Assignation')
             ->_as('assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->atomIs(array('String', 'Integer', 'Boolean', 'Float', 'Null', 'Arrayliteral', 'Ternary', 'Coalesce'))
             ->is('constant', true)
             ->inIs('DEFAULT')
             ->atomIs('Propertydefinition')
             ->not(
                $this->side()
                     ->outIs('DEFAULT')
                     ->hasNoIn('RIGHT')
             )
             ->back('assignation')
             ->outIs('LEFT');
        $this->prepareQuery();
    }
}

?>
