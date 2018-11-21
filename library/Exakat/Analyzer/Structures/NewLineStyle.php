<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class NewLineStyle extends Analyzer {

    public function analyze() {
        $mapping = <<<GREMLIN
if (it.get().label() == "String") {
    x2 = "slash-n"
} else {
    x2 = "PHP_EOL"
}

GREMLIN;
        $storage = array('\\n'     => 'slash-n',
                         'PHP_EOL' => 'PHP_EOL');

        $this->atomIs(array('String', 'Identifier', 'Nsname'))
             ->raw('coalesce( __.hasLabel("Identifier", "Nsname").has("fullnspath").has("fullnspath", "\\\\PHP_EOL"), 
                              __.hasLabel("String").has("noDelimiter", "\\\\n")
                             )')
             ->raw('map{ '.$mapping.' }')
             ->raw('groupCount("gf").cap("gf").sideEffect{ s = it.get().values().sum(); }');
        $types = $this->rawQuery()->toArray()[0];

        $store = array();
        $total = 0;
        foreach($storage as $key => $v) {
            $c = empty($types[$v]) ? 0 : $types[$v];
            $store[] = array('key'   => $key,
                             'value' => $c);
            $total += $c;
        }
        Analyzer::$datastore->addRowAnalyzer($this->analyzerQuoted, $store);

        if ($total == 0) {
            return;
        }

        $types = array_filter($types, function ($x) use ($total) { return $x > 0 && $x  < 0.1 *  $total; });
        if (empty($types)) {
            return;
        }

        $this->atomIs(array('String', 'Identifier', 'Nsname'))
             ->raw('coalesce( __.hasLabel("Identifier", "Nsname").has("fullnspath").has("fullnspath", "\\\\PHP_EOL"), 
                              __.hasLabel("String").has("noDelimiter", "\\\\n")
                             )')
             ->raw('sideEffect{ '.$mapping.' }')
             ->raw('filter{ x2 in ***}', $types)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
