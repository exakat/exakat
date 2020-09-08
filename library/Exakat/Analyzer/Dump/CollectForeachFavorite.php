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

namespace Exakat\Analyzer\Dump;

class CollectForeachFavorite extends AnalyzerArrayHashResults {
    protected $analyzerName = 'Foreach Names';

    public function analyze(): void {
        // Foreach, values only
        $this->atomIs('Foreach')
             ->hasNoOut('INDEX')
             ->outIs('VALUE')
             ->values('fullcode');
        $valuesOnly = $this->rawQuery();

        // Foreach, index only
        $this->atomIs('Foreach')
             ->hasOut('INDEX')
             ->outIs('VALUE')
             ->values('fullcode');
        $values = $this->rawQuery();

        $this->atomIs('Foreach')
             ->outIs('INDEX')
             ->values('fullcode');
        $keys = $this->rawQuery();

        $statsValues = array_count_values(array_merge($values->toArray(), $valuesOnly->toArray(), $keys->toArray()));
        $statsValues['None'] = count($valuesOnly);

        $valuesSQL = array();
        foreach($statsValues as $name => $count) {
            $valuesSQL[] = array($name, $count);
        }

        if (empty($valuesSQL)) {
            return;
        }

        $this->analyzerValues = $valuesSQL;

        $this->prepareQuery();
    }
}

?>
