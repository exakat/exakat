<?php

namespace Analyzer\Constants;

use Analyzer;

class IsExtConstant extends Analyzer\Analyzer {

    public function dependsOn() {
        return array("Analyzer\\Constants\\ConstantUsage");
    }
    
    public function analyze() {
        $exts = glob('library/Analyzer/Extensions/*.php');
        $exts[] = 'php_constants.ini';
        
        $constants = array();
        foreach($exts as $ext) {
            $inifile = str_replace('library/Analyzer/Extensions/Ext', '', str_replace('.php', '.ini', $ext));
            if ($inifile == 'library/Analyzer/Extensions/Used.ini') { continue; }
            $ini = $this->loadIni($inifile);
            
            if (!isset($ini['constants']) || !is_array($ini['constants'])) {
                print "No constants defined in $inifile\n";
            } else {
                if (!empty($ini['constants'][0])) {
                    $constants = array_merge($constants, $ini['constants']);
                }
            }
        }
        
        $this->analyzerIs("Analyzer\\Constants\\ConstantUsage")
             ->code($constants);
        $this->prepareQuery();
    }
}

?>