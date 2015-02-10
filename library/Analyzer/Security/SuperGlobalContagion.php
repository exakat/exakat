<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Security;

use Analyzer;

class SuperGlobalContagion extends Analyzer\Analyzer {
    public function analyze() {
        $vars = $this->loadIni('php_incoming.ini');
        $vars = $vars['incoming'];
        
        // $_get = $_GET;
        $this->atomIs("Assignation")
             ->outIs('RIGHT')
             ->atomIs('Variable')
             ->code($vars)
             ->back('first')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->codeIsNot($vars);
        $this->prepareQuery();

        // $_get = $_GET['3'];
        $this->atomIs("Assignation")
             ->outIs('RIGHT')
             ->atomIsNot('Variable')
             ->atomInside('Variable')
             ->code($vars)
             ->back('first')
             ->outIs('LEFT')
             ->_as('result')
             ->atomIs('Variable')
             ->codeIsNot($vars)
             ->back('result');
        $this->prepareQuery();

        // $_get['3'] = $_GET
        $this->atomIs("Assignation")
             ->outIs('RIGHT')
             ->atomIs('Variable')
             ->code($vars)
             ->back('first')
             ->outIs('LEFT')
             ->_as('result')
             ->atomIsNot('Variable')
             ->atomInside('Variable')
             ->codeIsNot($vars)
             ->back('result');
        $this->prepareQuery();
        
        // $_get['3'] = $_GET[1]
        $this->atomIs("Assignation")
             ->outIs('RIGHT')
             ->atomIsNot('Variable')
             ->atomInside('Variable')
             ->code($vars)
             ->back('first')
             ->outIs('LEFT')
             ->_as('result')
             ->atomIsNot('Variable')
             ->atomInside('Variable')
             ->codeIsNot($vars)
             ->back('result');
        $this->prepareQuery();        
        
        // propagation is not implemented yet.
        // current issue : propagating the found result to the next variables with the same name  
        
        /*
        ////////////////////////////////////////////////
        //first pass over the initial list
        for($i = 0; $i < 2; $i++) {
            // $_get_get = $_get;
            $this->atomIs("Assignation")
                 ->outIs('RIGHT')
                 ->atomIs('Variable')
                 ->analyzerIs('self')
                 ->back('first')
                 ->outIs('LEFT')
                 ->atomIs('Variable')
                 ->codeIsNot($vars);
            $this->prepareQuery();

            // $_get_get[2] = $_get;
            $this->atomIs("Assignation")
                 ->outIs('RIGHT')
                 ->atomInside('Variable')
                 ->analyzerIs('self')
                 ->back('first')
                 ->outIs('LEFT')
                 ->_as('result')
                 ->atomIs('Variable')
                 ->codeIsNot($vars)
                 ->back('result');
            $this->prepareQuery();

            // $_get_get = $_get;
            $this->atomIs("Assignation")
                 ->outIs('RIGHT')
                 ->atomInside('Variable')
                 ->analyzerIs('self')
                 ->back('first')
                 ->outIs('LEFT')
                 ->atomIs('Variable')
                 ->codeIsNot($vars);
            $this->prepareQuery();

            // $_get_get[2] = $_get;
            $this->atomIs("Assignation")
                 ->outIs('RIGHT')
                 ->atomInside('Variable')
                 ->analyzerIs('self')
                 ->back('first')
                 ->outIs('LEFT')
                 ->_as('result')
                 ->atomInside('Variable')
                 ->codeIsNot($vars)
                 ->back('result');
            $this->prepareQuery();

        }
            */
    }
}

?>
