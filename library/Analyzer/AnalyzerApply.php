<?php
namespace Analyzer;

class AnalyzerApply {
    protected $applyBelow = false; 

    public function __construct() {
        // empty... 
    }
    
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
applyBelowRoot.out.loop(1){true}{it.object.fullcode == x.fullcode}.each{ 
    g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED'); 
    it.setProperty('appliedBelow', true);
    c = c + 1;
}

GREMLIN;
        } else {
            $applyBelow = "";
        }

        return <<<GREMLIN
.each{
    g.addEdge(g.idx('analyzers')[['analyzer':'{$analyzer}']].next(), it, 'ANALYZED');
    
    // Apply below
    {$applyBelow}
    
    c = c + 1;
}
c;

GREMLIN;
        
    }

}

?>
