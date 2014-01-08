<?php

namespace Report;

class Report {
    private $client = null;
    private $summary = true;
    private $content = null;
    private $current = null;

    function __construct($client) {
        $this->client = $client;
        $this->content = new Section('');
        $this->current = $this->content;
    }
    
    function addSummary($add) {
        $this->summary = (bool) $add;
    }

    function createH1($name) {
        $this->current = $this->content->addSection($name, 1);
    }

    function createH2($name) {
        $this->current = $this->content->getCurrent()->addSection($name, 2);
    }

    function createH3($name) {
        $this->current = $this->content->getCurrent()->getCurrent()->addSection($name, 3);
    }

    function addContent($type, $data = null) {
        return $this->current->addContent($type, $data);
    }

    function toMarkdown() {
        $report = "Report Text\n\n";
        
        if ($this->summary) {
            $report = "# Summary\n";
        
            foreach($this->content->getSections() as $section) {
                $report .= "+ [".$section->getName()."](#".$section->getid().")\n";
                
                if ($subsections = $section->getSections()) {
                    foreach($subsections as $subsection) {
                        $report .= "    + [".$subsection->getName()."](#".$subsection->getid().")\n";

                        if ($subsubsections = $subsection->getSections()) {
                            foreach($subsubsections as $subsubsection) {
                                $report .= "        + [".$subsubsection->getName()."](#".$subsubsection->getid().")\n";
                            }
                        }

                    }
                }
            }
            $report .= "\n";
        }
        
        foreach($this->content->getSections() as $section) {
            $report .= $section->toMarkdown();
        }

        return $report;
    }
    
    function toText() {
        $report = "Report Text\n\n";
        
        if ($this->summary) {
            $report = "# Summary\n";
        
            foreach($this->content->getSections() as $section) {
                $report .= "+ [".$section->getName()."](#".$section->getid().")\n";
                
                if ($subsections = $section->getSections()) {
                    foreach($subsections as $subsection) {
                        $report .= "    + [".$subsection->getName()."](#".$subsection->getid().")\n";

                        if ($subsubsections = $subsection->getSections()) {
                            foreach($subsubsections as $subsubsection) {
                                $report .= "        + [".$subsubsection->getName()."](#".$subsubsection->getid().")\n";
                            }
                        }

                    }
                }
            }
            $report .= "\n";
        }
        
        foreach($this->content->getSections() as $section) {
            $report .= $section->toText();
        }

        return $report;
    }
}

?>