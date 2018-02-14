<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Analyzer\ZendF;

use Exakat\Analyzer\Analyzer;

class NoEchoOutsideView extends Analyzer {
    public function dependsOn() {
        return array('ZendF/IsView',
                    );
    }
    
    public function analyze() {
        $prints = $this->loadIni('php_prints.ini');
        
        $this->atomFunctionIs(makeFullNsPath($prints['functions']));
        $this->prepareQuery();

        $this->atomIs(array('Echo', 'Print'))
             ->goToFile()
             ->analyzerIsNot('ZendF/IsView')
             ->back('first');
        $this->prepareQuery();

        // print_r($a);
        $this->atomFunctionIs(makeFullNsPath($prints['functionsArg1']))
             ->noChildWithRank('ARGUMENT', 1)
             ->goToFile()
             ->analyzerIsNot('ZendF/IsView')
             ->back('first');
        $this->prepareQuery();

        // print_r($a, false);
        $this->atomFunctionIs(makeFullNsPath($prints['functionsArg1']))
             ->outWithRank('ARGUMENT', 1)
             ->is('boolean', false)
             ->goToFile()
             ->analyzerIsNot('ZendF/IsView')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Inlinehtml')
             ->goToFile()
             ->analyzerIsNot('ZendF/IsView')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
