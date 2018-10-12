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

namespace Exakat\Analyzer\Interfaces;

use Exakat\Analyzer\Analyzer;

class CouldUseInterface extends Analyzer {
    // interface i { function i(); }
    // class x { function i() {}}
    // class x could use interface i but it was forgotten

    public function analyze() {
        // Custom interfaces
        $query = <<<GREMLIN
g.V().hasLabel("Interface")
     .as("name")
     .out("METHOD", "MAGICMETHOD").as("methodCount").out("NAME").as("method")
     .select("name", "method", "methodCount").by("fullnspath").by("lccode").by("count");
GREMLIN;

        $res = $this->query($query)->toArray();

        $interfaces = array();
        foreach($res as $row) {
            if (isset($interfaces[$row['name']])) {
                $interfaces[$row['name']][] = "$row[method]-$row[methodCount]";
            } else {
                $interfaces[$row['name']] = array("$row[method]-$row[methodCount]");
            }
        }
        
        $phpInterfaces = $this->loadJson('php_interfaces_methods.json');
        foreach($phpInterfaces as $interface => $methods) {
            $translations = $this->dictCode->translate(array_column($methods, 'name'));
            if (count($methods) != count($translations)) {
                continue;
            }
            
            // translations are NOT in the same order than original
            foreach($methods as $method) {
                $interfaces[$interface][] = $translations[$method->name]."-$method->count";
            }
        }
        
        $this->atomIs(self::$CLASSES_ALL)
             ->collectImplements('interfaces')
             ->hasOut(array('METHOD', 'MAGICMETHOD'))
             ->raw('sideEffect{ x = []; }')
             ->raw('sideEffect{ i = *** }', $interfaces)
             // Collect methods names with argument count
             // can one implement an interface, but with wrong argument counts ? 
             ->raw(<<<'GREMLIN'
where( 
    __.out("METHOD", "MAGICMETHOD").sideEffect{ y = it.get().value("count"); }
      .sideEffect{ x.add(it.get().vertices(OUT, "NAME").next().value("lccode") + "-" + it.get().value("count") ) ; }
      .fold() 
)
GREMLIN
)
             ->raw(<<<GREMLIN
filter{
    a = false;
    i.each{ n, e ->
        if (x.intersect(e) == e) {
            a = true;
            fnp = n;
        }
    }
    
    a;
}

GREMLIN
)
                ->filter('!(fnp in interfaces) ')
                ->back('first');
        $this->prepareQuery();
    }
}

?>
