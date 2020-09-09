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


class CollectDefinitionsStats extends AnalyzerArrayHashResults {
    protected $analyzerName = 'definitionStats';

    public function analyze(): void {

        $types = array('Staticconstant'   => 'staticconstants',
                       'Staticmethodcall' => 'staticmethodcalls',
                       'Staticproperty'   => 'staticproperty',

                       'Methodcall'       => 'methodcalls',
                       'Member'           => 'members',
                        );

        $this->atomIs(array_keys($types))
             ->raw(<<<'GREMLIN'
groupCount("x").by(label).cap("x")
GREMLIN
);
        // Possibly empty when none of the above are used.
        $resAll = $this->rawQuery()->toArray()[0] ?? array();

        $this->atomIs(array_keys($types))
             ->raw(<<<'GREMLIN'
where(
    __.in("DEFINITION").not(hasLabel("Virtualproperty"))
).groupCount("m").by(label).cap("m")

GREMLIN
);
        // Possibly empty when none of the above are used.
        $resDefined = $this->rawQuery()->toArray()[0] ?? array();

        foreach($types as $label => $name) {
            $this->analyzerValues[] = array($name,
                                            $resAll[$label] ?? 0,
                                            );

            $this->analyzerValues[] = array($name . ' defined',
                                            $resDefined[$label] ?? 0,
                                            );
        }

        $this->prepareQuery();
    }
}

?>
