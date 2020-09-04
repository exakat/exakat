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

namespace Exakat\Analyzer\Typehints;

use Exakat\Analyzer\Analyzer;

class MissingReturntype extends Analyzer {
    public function dependsOn(): array {
        return array('Typehints/CouldBeNull',
                     'Typehints/CouldBeString',
                     'Typehints/CouldBeFloat',
                     'Typehints/CouldBeInt',
                     'Typehints/CouldBeBoolean',
                     'Typehints/CouldBeArray',
                     'Typehints/CouldBeCIT',
                    );
    }

    public function analyze(): void {
        // function foo() : string { return shell_exec('ls -hla'); }
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->atomIsNot('Void')          // absence of returntype
             ->fullnspathIsNot('\\void')  // void as return type
             ->back('first')

             ->collectTypehints('returntypes')
             ->raw(<<<'GREMLIN'
or(
    __.filter{!('\\string'  in returntypes); }.in("ANALYZED").has("analyzer", 'Typehints/CouldBeString'),
    __.filter{!('\\int'     in returntypes); }.in("ANALYZED").has("analyzer", 'Typehints/CouldBeInt'),
    __.filter{!('\\null'    in returntypes); }.in("ANALYZED").has("analyzer", 'Typehints/CouldBeNull'),
    __.filter{!('\\float'   in returntypes); }.in("ANALYZED").has("analyzer", 'Typehints/CouldBeFloat'),
    __.filter{!('\\bool'    in returntypes); }.in("ANALYZED").has("analyzer", 'Typehints/CouldBeBoolean'),
    __.filter{!('\\array'   in returntypes); }.in("ANALYZED").has("analyzer", 'Typehints/CouldBeArray'),
    __.not(where(__.out('RETURNTYPE').hasLabel("Identifier", "Nsname", "Self", "Static", "Parent"))).in("ANALYZED").has("analyzer", 'Typehints/CouldBeCIT')
)
GREMLIN
)

             ->back('first');
        $this->prepareQuery();
    }
}

?>
