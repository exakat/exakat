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

namespace Analyzer;

class AnalyzerApply {
    protected $applyBelow = false;

    public function setApplyBelow($applyBelow = true) {
        $this->applyBelow = $applyBelow;
        
        return $this;
    }

    public function setAnalyzer($analyzer) {
        $this->analyzer = $analyzer;
        
        return $this;
    }
    
    public function getGremlin() {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);

        if ($this->applyBelow) {
            $applyBelow = <<<GREMLIN

x = it;
// use code instead of fullcode (case of references!)
applyBelowRoot.out.loop(1){true}{it.object.code == x.code}.each{
    results.add(it);
    it.setProperty('appliedBelow', true);
    total++;
}

GREMLIN;
        } else {
            $applyBelow = '';
        }

        $analyzerShort = str_replace('Analyzer\\\\', '', $analyzer);

        return <<<GREMLIN
.each{
    results.add(it);
    
    // Apply below
    {$applyBelow}
    
    total++;
}

x = g.addVertex(null, [analyzer:'{$analyzer}', analyzer:true, line:0, description:'Analyzer index for {$analyzer}', code:'{$analyzer}', fullcode:'{$analyzerShort}',  atom:'Index', token:'T_INDEX']);
results.each{ g.addEdge(x, it, 'ANALYZED'); }
g.idx('analyzers').put('analyzer', '{$analyzer}', x);

['processed':processed, 'total':total];


GREMLIN;
        
    }

}

?>
