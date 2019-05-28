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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class UnusedFunctions extends Analyzer {
    public function dependsOn() {
        return array('Functions/Recursive',
                    );
    }

    public function analyze() {
        // function foo() {} // no foo();
        $this->atomIs('Function')
             ->fullnspathIsNot('\\__autoload')
             ->analyzerIsNot('Functions/Recursive')
             ->hasNoOut('DEFINITION');
             // Retired 'hasNoDefinition' : It needs a rename, and some checks
        $this->prepareQuery();

        // This depends on the order of the functions in the base, so we call it twice.
        // Review is needed : we may need more time, though we can't know when to stop.
        $this->linearlyUnusedFunction();
        $this->linearlyUnusedFunction();
    }
    
    private function linearlyUnusedFunction() {
       // level 2 of unused : only used by unused functions
       // function foo() {} // no foo();
       // This depends on the order of the functions in the base!!!
       $this->atomIs('Function')
            ->fullnspathIsNot('\\__autoload')
            ->savePropertyAs('fullnspath', 'fnp')
            ->analyzerIsNot('self')
            // Check for recursive
            // Check for already dead calling function
            ->not(
                $this->side()
                     ->outIs('DEFINITION')
                     ->goToInstruction(array('Function', 'Closure', 'File'))
                     ->raw(<<<'GREMLIN'
coalesce( __.hasLabel("File", "Closure"), 
          __.hasLabel("Function").filter{ it.get().value("fullnspath") != fnp;}
                                 .not(where( __.in("ANALYZED").has("analyzer", "Functions/UnusedFunctions"))))

GREMLIN
)
        );
        $this->prepareQuery();
    }
}
?>
