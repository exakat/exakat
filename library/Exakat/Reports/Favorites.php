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


namespace Exakat\Reports;

class Favorites extends Reports {
    const FILE_EXTENSION = 'json';
    const FILE_FILENAME  = 'favorites';

    public function _generate(array $analyzerList): string {
        $analyzers = $this->rulesets->getRulesetsAnalyzers(array('Preferences'));

        $return = array();
        foreach($analyzers as $analyzer) {
            $r = $this->dump->fetchHashAnalyzer($analyzer)->toArray();

            if (empty($r)) {
                continue;
            }

            $return[$analyzer] = $r;
            $this->count();
        }

        return json_encode($return, JSON_PRETTY_PRINT);
    }
}

?>