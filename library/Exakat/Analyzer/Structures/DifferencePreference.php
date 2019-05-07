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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class DifferencePreference extends Analyzer {
    public function analyze() {
        $different = $this->dictCode->translate(array('!='));

        if (empty($different)) {
            $different = array(-1); // always false
        }

        $mapping = <<<GREMLIN
if (it.get().value("code") == $different[0]) {
    x2 = "!=";
} else {
    x2 = "<>";
}
GREMLIN;
        $storage = array('!='  => '!=',
                         '<>'  => '<>');

        $this->atomIs('Comparison')
             ->codeIs(array('!=', '<>'))
             ->raw('map{ ' . $mapping . ' }')
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

        $types = array_filter($types, function ($x) use ($total) { return $x > 0 && $x / $total < 0.1; });
        if (empty($types)) {
            return;
        }

        $this->atomIs('Comparison')
             ->codeIs(array('!=', '<>'))
             ->raw('map{ ' . $mapping . ' }')
             ->raw('filter{ x2 in ***}', $types)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
