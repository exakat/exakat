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

namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class IntegerConversion extends Analyzer {
    private $dependsOn = null;
    public function dependsOn() {
        if ($this->dependsOn === null) {
            $module = $this->themes->getInstance('Modules/IncomingValues', $this->gremlin, $this->config);
            $modules = $module->dependsOn();
    
            $this->dependsOn = array_merge( array('Php/IncomingValues'),
                                            $modules
                                          );
        }
                                      
        return $this->dependsOn;
    }

    public function analyze() {
        $incomingValues = $this->dependsOn();

        // $a = $_GET['a']; if ($a == 3) {}
        $this->atomIs('Variable')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->_as('results')
             ->codeIsNot(array('===', '!=='), self::TRANSLATE, self::CASE_SENSITIVE)
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Integer', Analyzer::WITH_CONSTANTS)
             ->back('first')
             ->inIs('DEFINITION')
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->analyzerIs($incomingValues)
             ->back('results');
        $this->prepareQuery();

        // foo($_GET['a']); function foo() {if ($a == 3) {} }
        $this->atomIs('Variable')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->_as('results')
             ->codeIsNot(array('===', '!=='), self::TRANSLATE, self::CASE_SENSITIVE)
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Integer', Analyzer::WITH_CONSTANTS)
             ->back('first')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             // methods, closures, functions...
             ->outIs('DEFINITION')
             ->outWithRank('ARGUMENT', 'ranked')
             ->analyzerIs($incomingValues)
             ->back('results');
        $this->prepareQuery();

        // if ($_COOKIES['a'] == 3) {} }
        $this->analyzerIs($incomingValues)
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->codeIsNot(array('===', '!=='), self::TRANSLATE, self::CASE_SENSITIVE)
             ->_as('results')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Integer', Analyzer::WITH_CONSTANTS)
             ->back('results');
        $this->prepareQuery();

        // if ((int) $_COOKIES['a'] === 3) {} }
        $this->analyzerIs($incomingValues)
             ->inIsIE('VARIABLE')
             ->inIs('CAST')
             ->tokenIs('T_INT_CAST') // casting to integer
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             // operator doesn't matter : it is hidden by cast
             ->_as('results')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Integer', Analyzer::WITH_CONSTANTS)
             ->back('results');
        $this->prepareQuery();
    }
}

?>
