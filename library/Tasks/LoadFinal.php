<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Tasks;

class LoadFinal extends Tasks {
    public function run(\Config $config) {
        
        $linksIn = \Tokenizer\Token::linksAsList();
        
        // processing '\parent' fullnspath
        $query = <<<GREMLIN
g.V().hasLabel("Identifier").filter{ it.get().value("fullnspath").toLowerCase() == "\\\\parent"}
.where( __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("EXTENDS") )
.property('fullnspath', __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("EXTENDS").values("fullnspath") )
.addE('DEFINITION').from( __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("EXTENDS").in("DEFINITION") )

GREMLIN;
        $this->gremlin->query($query);
        display("\\parent to fullnspath\n");

        // processing '\self' fullnspath
        $query = <<<GREMLIN
g.V().hasLabel("Identifier").filter{ it.get().value("fullnspath").toLowerCase() == "\\\\self"}
.property('fullnspath', __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("NAME").values("fullnspath") )
.addE('DEFINITION').from( __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)) )

GREMLIN;
        $this->gremlin->query($query);
        display('\\self to fullnspath');
        
        // processing '\static' fullnspath
        $query = <<<GREMLIN
g.V().hasLabel("Identifier").filter{ it.get().value("fullnspath").toLowerCase() == "\\\\static"}
.property('fullnspath', __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("NAME").values("fullnspath") )
.addE('DEFINITION').from( __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)) )

GREMLIN;
        $this->gremlin->query($query);
        display('\\static to fullnspath');

        // update fullnspath with fallback for functions 
        //

        $query = <<<GREMLIN
g.V().hasLabel("Ppp").out("PPP").coalesce( out("LEFT"), __.filter{ true } )
.sideEffect{ it.get().property('propertyname', it.get().value('code').toString().substring(1, it.get().value('code').size())); }

GREMLIN;
        $this->gremlin->query($query);
        display('set propertyname');
        
    }
}

?>
