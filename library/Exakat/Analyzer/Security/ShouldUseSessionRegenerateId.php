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

namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class ShouldUseSessionRegenerateId extends Analyzer {
    public function dependsOn() {
        return array('Extensions/Extsession',
                    );
    }
    
    public function analyze() {
        $sessions = $this->query(<<<GREMLIN
g.V().hasLabel("Analyze")
     .has("analyzer", "Extensions/Extsession")
     .out("ANALYZED")
     .count()
GREMLIN
);

        // No session, no regenerateId
        if (empty($sessions)) {
            return ;
        }
        $regenerateid = $this->query('g.V().hasLabel("Functioncall")
                                           .has("fullnspath")
                                           .has("fullnspath", "\\\\session_regenerate_id")
                                           .count()');
                                           
        if ($regenerateid[0] !== 0) {
            return;
        }

        $this->atomIs('Project');
        $this->prepareQuery();
    }
}

?>
