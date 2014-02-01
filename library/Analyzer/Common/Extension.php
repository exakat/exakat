<?php

namespace Analyzer\Common;

use Analyzer;

class Extension extends Analyzer\Analyzer {
    protected $source = '';
    
    public function dependsOn() {
        return array("Analyzer\\Classes\\ClassUsage",
                     "Analyzer\\Interfaces\\InterfaceUsage");
    }
    
    public function analyze() {
        $functions = array();
        $constants = array();
        $classes = array();
        $interfaces = array();

        if (substr($this->source, -4) == '.ini') {
            $ini = parse_ini_file(dirname(dirname(dirname(__DIR__))).'/data/'.$this->source);
            extract($ini);
        } elseif (substr($this->source, -4) == '.txt') {
            $functions = file(dirname(dirname(dirname(__DIR__))).'/data/'.$this->source);
        } else {
            print "Cannot process the '{$this->source}' file. Sorry\n";
            return true;
        }
        
        if (!empty($functions)) {
            $this->atomIs("Functioncall")
                 ->namespaceIs('Global')
                 ->code($functions);
            $this->prepareQuery();

            $functions = array_map(function ($x) { return "\\\\".$x; } ,  $functions);
            $this->atomIs("Functioncall")
                 ->code($functions);
            $this->prepareQuery();
        }
        
        /*
        Not yet supported.
        if (!empty($constants)) {
            $this->atomIs("ConstantUsage")
                 ->code($functions);
            $this->prepareQuery();
        }
        */

        if (!empty($classes)) {
            $this->analyzerIs("Analyzer\\Classes\\ClassUsage")
                 ->code($classes);
            $this->prepareQuery();

            $classes = array_map(function ($x) { return "\\".$x; } ,  $classes);
            $this->analyzerIs("Analyzer\\Classes\\ClassUsage")
                 ->fullcode($classes);
                 ;
            $this->prepareQuery();

            $this->analyzerIs("Analyzer\\Classes\\ClassUsage")
                 ->code($classes);
            $this->prepareQuery();
        }

        if (!empty($interfaces)) {
            $this->analyzerIs("Analyzer\\Interfaces\\InterfaceUsage")
                 ->code($interfaces);
            $this->prepareQuery();

            $interfaces = array_map(function ($x) { return "\\".$x; } ,  $interfaces);
            $this->analyzerIs("Analyzer\\Interfaces\\InterfaceUsage")
                 ->fullcode($interfaces);
            $this->prepareQuery();
        }
    }
}

?>