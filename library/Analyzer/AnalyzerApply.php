<?php
namespace Analyzer;

class AnalyzerApply {
    protected $apply_below = false; 

    public function __construct() {
        // empty... 
    }
    
    public function setApplyBelow($apply_below = true) {
        $this->apply_below = $apply_below;
        
        return $this;
    }

    public function setAnalyzer($analyzer) {
        $this->analyzer = $analyzer;
        
        return $this;
    }
    
    public function getGremlin() {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        if ($this->apply_below) {
            $apply_below = <<<GREMLIN
x = it;
it.in("VALUE").            out('LOOP').out.loop(1){it.loops < 100}{it.object.code == x.code}.each{ g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED'); }
it.in('KEY').in("VALUE").  out('LOOP').out.loop(1){it.loops < 100}{it.object.code == x.code}.each{ g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED'); }
it.in('VALUE').in("VALUE").out('LOOP').out.loop(1){it.loops < 100}{it.object.code == x.code}.each{ g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED'); }
it.in('ARGUMENT').in("ARGUMENTS").out('BLOCK').out.loop(1){it.loops < 100}{it.object.code == x.code}.each{ g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED'); }

GREMLIN;
        } else {
            $apply_below = "";
        }

        return <<<GREMLIN
.each{
    g.addEdge(g.idx('analyzers')[['analyzer':'{$analyzer}']].next(), it, 'ANALYZED');
    
    // Apply below
    {$apply_below}
    
    c = c + 1;
}
c;

GREMLIN;
        
    }

}

?>
