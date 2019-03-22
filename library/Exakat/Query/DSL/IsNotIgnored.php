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


namespace Exakat\Query\DSL;

use Exakat\Query\Query;
use Exakat\Analyzer\Analyzer;

class IsNotIgnored extends DSL {
    const IGNORED_CLASSES = 1;
    const IGNORED_FUNCTIONS = 2;
    const IGNORED_CONSTANTS = 3;
    
    public function run() : Command {
        list($type, ) = func_get_args();
        
        switch($type) {
            case IsNotIgnored::IGNORED_CLASSES :
                $fullnspath = $this->ignoredcit;
                break;
            case IsNotIgnored::IGNORED_CONSTANTS :
                $fullnspath = $this->ignoredconstants;
                break;
            case IsNotIgnored::IGNORED_FUNCTIONS :
                $fullnspath = $this->ignoredfunctions;
                break;
            default : 
                throw new \Exception('Unknown type of ignored structure');
        }

        if (empty($fullnspath)) {
            return new Command(Query::NO_QUERY);
        }

        $has = $this->dslfactory->factory('has');
        $return = $has->run('fullnspath');

        $propertyIsNot = $this->dslfactory->factory('propertyIsNot');
        
        return $return->add($propertyIsNot->run('fullnspath', $fullnspath, Analyzer::CASE_SENSITIVE));
    }
}
?>
