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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class UselessGlobal extends Analyzer {
    public function dependsOn() {
        return array('Variables/VariableUsedOnceByContext',
                     'Structures/UnusedGlobal',
                    );
    }
    
    public function analyze() {
        $globals = $this->dictCode->translate(array('$GLOBALS'));

        // Global are unused if used only once
        $query = <<<GREMLIN
g.V().hasLabel("Globaldefinition").values("code")
GREMLIN;
        $inglobal = $this->query($query)->toArray();

        if (empty($globals)) {
            $inGlobals = array();
        } else {
            $query = <<<GREMLIN
g.V().hasLabel("Phpvariable").has("code", {$globals[0]}).in("VARIABLE").hasLabel("Array").values("globalvar")
GREMLIN;
            $inGlobals = $this->query($query)->toArray();
        }

        $MAX_LOOPING = self::MAX_LOOPING;
        $query = <<<GREMLIN
g.V().hasLabel("Php").out("CODE")
     .repeat(__.out({$this->linksDown}).not(hasLabel("Function", "Class", "Classanonymous", "Closure", "Trait", "Interface")) ).emit().times($MAX_LOOPING)
     .hasLabel("Variable").values("code");
GREMLIN;
        $implicitGLobals = $this->query($query)->toArray();

        $counts = array_count_values(array_merge($inGlobals, $inglobal, $implicitGLobals));
        $loneGlobal = array_filter($counts, function ($x) { return $x == 1; });
        $loneGlobal = array_keys($loneGlobal);

        if (!empty($loneGlobal)) {
            $this->atomIs('Globaldefinition')
                 ->codeIs($loneGlobal, self::NO_TRANSLATE, self::CASE_SENSITIVE);
            $this->prepareQuery();
            
            if (!empty($globals)) {
                $this->atomIs('Phpvariable')
                     ->codeIs($globals, self::NO_TRANSLATE, self::CASE_SENSITIVE)
                     ->inIs('VARIABLE')
                     ->atomIs('Array')
                     ->is('globalvar', $loneGlobal);
                $this->prepareQuery();
            }
        }

        // used only once

        // written only
    }
}

?>
