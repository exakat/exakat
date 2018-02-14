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
namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;

class MiddleVersion extends Analyzer {
    private $bugfixes = array();

    public function dependsOn() {
        $data = new Methods($this->config);
        $this->bugfixes = $data->getBugFixes();
        
        $depends = array();
        foreach($this->bugfixes as $bugfix) {
            if (!empty($bugfix['analyzer'])) {
                $depends[] = $bugfix['analyzer'];
            }
        }
        
        return $depends;
    }
    
    public function analyze() {
        // bugfixes based on functions
        $functions = array();
        foreach($this->bugfixes as $bugfix) {
            if (!empty($bugfix['function'])) {
                $functions[] = $bugfix['function'];
            }
        }
        $this->atomFunctionIs(makeFullNsPath($functions));
        $this->prepareQuery();

        // bugfixes based on analyzers
        foreach($this->bugfixes as $bugfix) {
            if (!empty($bugfix['analyzer'])) {
                $this->analyzerIs($bugfix['analyzer'])
                     ->ignore();
                $this->prepareQuery();
            }
        }
    }
}

?>
