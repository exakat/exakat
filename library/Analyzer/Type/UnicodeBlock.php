<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Type;

use Analyzer;

class UnicodeBlock extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs(array('String', 'HereDoc', 'NowDoc'));
    }

    public function toArray() {
        $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\Type\\\\UnicodeBlock']].out.hasNot('unicode_block', null)";
        $vertices = $this->query($queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices as $v) {
                $report[] = $v[0]->unicode_block;
            }
        }
        
        return $report;
    }

    public function toCountedArray($load = 'it.fullcode') {
        $queryTemplate = "m = [:]; g.idx('analyzers')[['analyzer':'Analyzer\\\\Type\\\\UnicodeBlock']].out.hasNot('unicode_block', null).groupCount(m){it.unicode_block}.cap";
        $vertices = $this->query($queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices[0][0] as $k => $v) {
                $report[$k] = $v;
            }
        }
        
        return $report;
    }
}

?>
