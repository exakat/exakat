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

class CollectPhpStructures extends AnalyzerTable {
    protected $analyzerName = 'phpStructures';

    protected $analyzerTable = 'phpStructures';

    protected $analyzerSQLTable = <<<'SQL'
CREATE TABLE phpStructures (id INTEGER PRIMARY KEY AUTOINCREMENT,
                            name STRING,
                            type STRING,
                            count INTEGER
)
SQL;

    public function dependsOn(): array {
        return array('Functions/IsExtFunction',
                     'Constants/IsExtConstant',
                     'Interfaces/IsExtInterface',
                     'Traits/IsExtTrait',
                     'Classes/IsExtClass',
                    );
    }

    public function analyze(): void {
        $this->collectPhpStructures2('Functioncall',                           'Functions/IsExtFunction',   'function');
        $this->collectPhpStructures2(array('Identifier', 'Nsname'),            'Constants/IsExtConstant',   'constant');
        $this->collectPhpStructures2(array('Identifier', 'Nsname'),            'Interfaces/IsExtInterface', 'interface');
        $this->collectPhpStructures2(array('Identifier', 'Nsname'),            'Traits/IsExtTrait',         'trait');
        $this->collectPhpStructures2(array('Newcall', 'Identifier', 'Nsname'), 'Classes/IsExtClass',        'class');
    }

    private function collectPhpStructures2($label, string $analyzer, string $type): void {
        $this->atomIs($label)
             ->analyzerIs($analyzer)
             ->raw('groupCount("m").by("fullnspath").cap("m").map{ x = []; for(key in it.get().keySet()) { x.add(["type":"' . $type . '", "name":key, "count":it.get().getAt(key)]);}; x }[0]');
        $this->prepareQuery();
    }
}

?>
