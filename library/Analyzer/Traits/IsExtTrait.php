<?php

namespace Analyzer\Traits;

use Analyzer;

class IsExtTrait extends Analyzer\Analyzer {

    public function dependsOn() {
        return array("Analyzer\\Traits\\TraitUsage");
    }
    
    public function analyze() {
        $exts = glob('library/Analyzer/Extensions/*.php');
        $exts[] = 'php_traits.ini';
        
        $traits = array();
        foreach($exts as $ext) {
            $inifile = str_replace('library/Analyzer/Extensions/Ext', '', str_replace('.php', '.ini', $ext));
            if ($inifile == 'library/Analyzer/Extensions/Used.ini') { continue; }
            $ini = $this->loadIni($inifile);
            
            if (!isset($ini['traits']) || !is_array($ini['traits'])) {
//                print "No trait defined in $inifile\n";
            } else {
                if (!empty($ini['traits'][0])) {
                    $traits = array_merge($traits, $ini['traits']);
                }
            }
        }

        $traits = $this->makeFullNsPath($traits)
        
        // no need to process anything!
        if (empty($traits)) { return true; } 
        
        $this->analyzerIs("Analyzer\\Traits\\TraitUsage")
             ->fullnspath($traits);
    }
}

?>
