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

namespace Exakat\Tasks\LoadFinal;

use Exakat\Analyzer\Analyzer;

class IsInIgnoredDir extends LoadFinal {
    public function run(): void {
        $ignoredfunctions = $this->datastore->getCol('ignoredfunctions', 'fullnspath');

        $countF = 0;
        if (!empty($ignoredfunctions)) {
            $query = $this->newQuery('IsInIgnoredDir functions');
            $query->atomIs('Functioncall', Analyzer::WITHOUT_CONSTANTS)
                  ->fullnspathIs($ignoredfunctions, Analyzer::CASE_SENSITIVE)
                  ->property('ignored_dir', true)
                  ->returnCount();
            $query->prepareRawQuery();
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countF = $result->toInt();
        }

        $ignoredconstants = $this->datastore->getCol('ignoredconstants', 'fullnspath');
        $ignoredcit       = $this->datastore->getCol('ignoredcit', 'fullnspath');
        $ignored          = array_values(array_merge($ignoredcit, $ignoredconstants));

        $countC = 0;
        if (!empty($ignoredfunctions)) {
            $query = $this->newQuery('IsInIgnoredDir constants + cit');
            $query->atomIs(array('Identifier', 'Nsname'), Analyzer::WITHOUT_CONSTANTS)
                  ->fullnspathIs($ignored, Analyzer::CASE_SENSITIVE)
                  ->property('ignored_dir', true)
                  ->returnCount();
            $query->prepareRawQuery();
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countC = $result->toInt();
        }

        $count = $countF + $countC;
        display("Set $count functions, constants and class with ignored_dir");
    }
}

?>
