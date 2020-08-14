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


namespace Exakat\Analyzer\Common;

use Exakat\Analyzer\Analyzer;

class Type extends Analyzer {

    protected $type = null;

    public function analyze(): void {
        $this->atomIs($this->type);
        $this->prepareQuery();
    }

    public function getDump(): array {
        $query = <<<GREMLIN
g.V().hasLabel("{$this->type}")
.sideEffect{ line = it.get().value('line');
             fullcode = it.get().value('fullcode');
             file='None'; 
             theFunction = 'None'; 
             theClass='None'; 
             theNamespace='None'; 
             }
.sideEffect{ line = it.get().value('line'); }
.until( hasLabel('File') ).repeat( 
    __.in($this->linksDown)
      .sideEffect{ if (it.get().label() == 'Function') { theFunction = it.get().value('code')} }
      .sideEffect{ if (it.get().label() in ['Class']) { theClass = it.get().value('fullcode')} }
       )
.sideEffect{  file = it.get().value('fullcode');}

.map{ ['fullcode':fullcode, 'file':file, 'line':line, 'namespace':theNamespace, 'class':theClass, 'function':theFunction ];}

GREMLIN;

        return $this->gremlin->query($query)->toArray();
    }
}

?>
