<?php

namespace Analyzer\Common;

use Analyzer;

class Extension extends Analyzer\Analyzer {
    protected $source = '';
    
    public function analyze() {
        if (substr($this->source, -4) == '.ini') {
            $ini = parse_ini_file(dirname(dirname(dirname(__DIR__))).'/data/'.$this->source);
            extract($ini);
        } elseif (substr($this->source, -4) == '.txt') {
            $functions = file(dirname(dirname(dirname(__DIR__))).'/data/'.$this->source);
            $constants = array();
            $classes = array();
        } else {
            print "Cannot process the '{$this->source}' file. Sorry\n";
            return true;
        }
        
        if (!empty($functions)) {
            $this->atomIs("Functioncall")
                 ->code($functions);
            $this->prepareQuery();
        }
        
        /*
        if (!empty($constants)) {
            $this->atomIs("ConstantUsage")
                 ->code($functions);
            $this->prepareQuery();
        }

        if (!empty($classes)) {
            $this->atomIs("ClasseUsage")
                 ->code($functions);
            $this->prepareQuery();
        }
        */
    }
}

?>