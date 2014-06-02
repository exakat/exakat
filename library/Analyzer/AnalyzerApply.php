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
applyBelowRoot.out.loop(1){true}{it.object.fullcode == x.fullcode}.each{ 
    g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED'); 
    it.setProperty('appliedBelow', true);
    c = c + 1;
}

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
