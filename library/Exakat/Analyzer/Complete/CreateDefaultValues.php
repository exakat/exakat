<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy Ð Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Complete;

use Exakat\Query\DSL\FollowParAs;

class CreateDefaultValues extends Complete {
    public function dependsOn(): array {
        return array( 'Complete/OverwrittenProperties',
                    );
    }
    public function analyze(): void {

        // Link initial values for containers
        $this->atomIs(array('Variabledefinition',
                            'Staticdefinition',
                            'Globaldefinition',
                            'Staticdefinition',
                            'Virtualproperty',
                            'Propertydefinition',
                            'Parametername',
                            ), self::WITHOUT_CONSTANTS)
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation', self::WITHOUT_CONSTANTS)
             ->codeIs(array('=', '??='), self::TRANSLATE, self::CASE_SENSITIVE) // can't accept .=, +=, etc.

             // doesn't use self : $a = $a + 1 is not a default value
             ->not(
                $this->side()
                     ->outIs('RIGHT')
                     ->atomInsideNoDefinition(self::VARIABLES_ALL)
                     ->inIs('DEFINITION')
                     ->inIsIE('NAME')
                     ->raw('is(eq("first"))')
             )
             ->outIs('RIGHT')
             ->followParAs(FollowParAs::FOLLOW_NONE)

             // 'Variableobject', 'Variablearray' are never on the right side of an assignation (not directly)
             ->not(
                $this->side()
                     ->atomIs('Variable')
                     ->inIs('DEFINITION')
                     ->inIsIE('NAME')
                     ->raw('is(eq("first"))')
             )
             ->addEFrom('DEFAULT', 'first');
        $this->prepareQuery();

        // With comparisons
        $this->atomIs(array('Variabledefinition',
                            'Staticdefinition',
                            'Globaldefinition',
                            'Staticdefinition',
                            'Virtualproperty',
                            'Propertydefinition',
                            'Parametername',
                            ), self::WITHOUT_CONSTANTS)
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison', self::WITHOUT_CONSTANTS)
             ->codeIs(array('==', '!=', '===', '!==', ), self::TRANSLATE, self::CASE_SENSITIVE)
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Integer', 'String'), self::WITH_CONSTANTS)
             ->addEFrom('DEFAULT', 'first');
        $this->prepareQuery();

        // With switch/match
        $this->atomIs(array('Variabledefinition',
                            'Staticdefinition',
                            'Globaldefinition',
                            'Staticdefinition',
                            'Virtualproperty',
                            'Propertydefinition',
                            'Parametername',
                            ), self::WITHOUT_CONSTANTS)
             ->outIs('DEFINITION')
             ->inIs('CONDITION')
             ->atomIs(self::SWITCH_ALL)
             ->outIs('CASES')
             ->outIs('EXPRESSION')
             ->atomIs('Case')
             ->outIs('CASE')
             ->atomIs(array('Integer', 'String'), self::WITH_CONSTANTS)
             ->not(
                $this->side()
                     ->inIs('DEFAULT')
                     ->raw('is(neq("first"))')
             )
             ->addEFrom('DEFAULT', 'first');
        $this->prepareQuery();

        // With foreach($a as $v)
        $this->atomIs(array('Variabledefinition',
                            'Staticdefinition',
                            'Globaldefinition',
                            'Staticdefinition',
                            'Virtualproperty',
                            'Propertydefinition',
                            'Parametername',
                            ), self::WITHOUT_CONSTANTS)
             ->outIs('DEFINITION')
             ->inIs('VALUE')
             ->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Arrayliteral', self::WITH_CONSTANTS)
             ->outIs('ARGUMENT')
             ->outIs('VALUE')
             ->atomIs(array('Integer', 'String'), self::WITH_CONSTANTS)
             ->not(
                $this->side()
                     ->inIs('DEFAULT')
                     ->raw('is(neq("first"))')
             )
             ->addEFrom('DEFAULT', 'first');
        $this->prepareQuery();

        // With foreach($a as $k => $v)
        $this->atomIs(array('Variabledefinition',
                            'Staticdefinition',
                            'Globaldefinition',
                            'Staticdefinition',
                            'Virtualproperty',
                            'Propertydefinition',
                            'Parametername',
                            ), self::WITHOUT_CONSTANTS)
             ->outIs('DEFINITION')
             ->inIs('INDEX')
             ->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Arrayliteral', self::WITH_CONSTANTS)
             ->outIs('ARGUMENT')
             ->outIsIE('INDEX')
             ->atomIs(array('Integer', 'String'), self::WITH_CONSTANTS)
             ->not(
                $this->side()
                     ->inIs('DEFAULT')
                     ->raw('is(neq("first"))')
             )
             ->addEFrom('DEFAULT', 'first');
        $this->prepareQuery();

        // propagate virtualproperties to original definition
        // This one must be the final of this analysis
        $this->atomIs(array('Propertydefinition'), self::WITHOUT_CONSTANTS)
             ->inIs('OVERWRITE')
             ->outIs('DEFAULT')
             ->not(
                $this->side()
                     ->inIs('DEFAULT')
                     ->raw('is(neq("first"))')
             )
             ->atomIsNot('Void')
             ->addEFrom('DEFAULT', 'first');
        $this->prepareQuery();
    }
}

?>
