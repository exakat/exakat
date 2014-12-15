<?php

namespace Analyzer\Constants;

use Analyzer;

class CustomConstantUsage extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Constants\\ConstantUsage');
    }
    
    public function analyze() {
        $exts = glob('library/Analyzer/Extensions/*.php');
        $exts[] = 'php_functions.ini';
        
        $constants = array();
        foreach($exts as $ext) {
            $inifile = str_replace('library/Analyzer/Extensions/Ext', '', str_replace('.php', '.ini', $ext));
            if ($inifile == 'library/Analyzer/Extensions/Used.ini') { continue; }
            $ini = $this->loadIni($inifile);
            
            if (!isset($ini['constants']) || !is_array($ini['constants'])) {
                print "No functions defined in $inifile\n";
            } else {
                if (!empty($ini['constants'][0])) {
                    $constants = array_merge($constants, array_map( function ($x) { return '\\'. strtolower($x); }, $ini['constants']));
                }
            }
        }

        $this->atomIs("Identifier")
             ->analyzerIs('Analyzer\\Constants\\ConstantUsage')
             ->fullnspathIsNot($constants);
        $this->prepareQuery();

        // @note NSnamed are OK by default (mmm, no!)
        $this->atomIs("Nsname")
             ->analyzerIs('Analyzer\\Constants\\ConstantUsage')
             ->fullnspathIsNot($constants);
        $this->prepareQuery();
    }
}

?>
