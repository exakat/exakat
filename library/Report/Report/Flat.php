<?php

namespace Report\Report;

class Flat {
    private $client = null;
    private $summary = true;
    private $content = null;
    private $current = null;

    private $root    = null;

    public function __construct($client) {
        $this->client = $client;
        $this->content = new \Report\Template\Section('');
        $this->current = $this->content;
        $this->root    = $this->content;
    }

    public function setProject($project) {
        $this->project = $project;
    }
    
    public function prepare() {
        ///// Application analyzes 
        $analyzes = array('Structures\\StrposCompare', 
                          'Structures\\Iffectation',
                          'Structures\\ErrorReportingWithInteger',
                          'Structures\\ForWithFunctioncall',
                          'Structures\\ForeachSourceNotVariable',
                          'Variables\\VariableUsedOnce',
                          'Variables\\VariableNonascii',
                          'Structures\\EvalUsage',
                          'Structures\\OnceUsage',
                          'Structures\\VardumpUsage',
                          'Structures\\PhpinfoUsage',
                          'Classes\\NonPpp',
                          'Php/Incompilable',
                          'Constants/ConstantStrangeNames',

                          'Structures\\NotNot',
                          'Structures\\Noscream',
                          'Structures\\toStringThrowsException',
                          'Structures\\CalltimePassByReference',
                          'Structures\\Break0',
                          'Structures\\BreakNonInteger',
                          );

        // hash with config
        /*
        foreach($analyzes as $id => $a) {
            if (!in_array(str_replace('\\', '/', $a), $config['analyzer'])) {
                unset($analyzes[$id]);
            }
        }
        */

        if (count($analyzes) > 0) {
            $h1 = false;
            foreach($analyzes as $a) {
                $analyzer = \Analyzer\Analyzer::getInstance($a, $this->client);
        
                /*
                if (!$analyzer->checkPhpVersion('5.3.26')) {
                    $this->incompatible[] = $analyzer->getName();
                    continue; 
                }

                if (!$analyzer->checkPhpConfiguration('aspTags')) {
                    $this->incompatible[] = $analyzer->getName();
                    continue; 
                }
                */
                
                /*
                
                if ($analyzer->hasResults()) {
                    $this->no_output[] = $analyzer->getName();
                    continue;
                }
                */

                $list = $this->addContent('Horizontal', $analyzer);
            }
        }
        
        return true;
    }
    
    function render($format, $filename = null) {
    /*
        $this->output = new \Report\Format\Text();
        
        foreach($this->content->getContent() as $c) {
            $c->render($this->output);
        }
        
        if (isset($filename)) {
            $this->output->toFile($filename.'.txt');
        }

        $this->output = new \Report\Format\Html();
        
        foreach($this->content->getContent() as $c) {
            $c->render($this->output);
        }
        
        if (isset($filename)) {
            $this->output->toFile($filename.'.html');
        }

        $this->output = new \Report\Format\Csv();
        
        foreach($this->content->getContent() as $c) {
            $c->render($this->output);
        }
        
        if (isset($filename)) {
            $this->output->toFile($filename.'.csv');
        }

        $this->output = new \Report\Format\Ace();
        
        foreach($this->content->getContent() as $c) {
            $c->render($this->output);
        }
        
        if (isset($filename)) {
            $this->output->toFile('ace/table.html');
        }
        */

        $class = "\\Report\\Format\\$format";
        $this->output = new $class();
        
        foreach($this->root->getContent() as $c) {
            $c->render($this->output);
        }
        
        if (isset($filename)) {
            return $this->output->toFile($filename.'.'.$this->output->getExtension());
        } else {
            die("No filename?");
        }

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
}

?>