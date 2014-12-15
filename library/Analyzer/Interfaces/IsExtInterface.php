<?php

namespace Analyzer\Interfaces;

use Analyzer;

class IsExtInterface extends Analyzer\Analyzer {

    public function dependsOn() {
        return array("Analyzer\\Interfaces\\InterfaceUsage");
    }
    
    public function analyze() {
        $exts = glob('library/Analyzer/Extensions/*.php');
        $exts[] = 'php_interfaces.ini';
        
        $interfaces = array();
        foreach($exts as $ext) {
            $inifile = str_replace('library/Analyzer/Extensions/Ext', '', str_replace('.php', '.ini', $ext));
            if ($inifile == 'library/Analyzer/Extensions/Used.ini') { continue; }
            $ini = $this->loadIni($inifile);
            
            if (!isset($ini['interfaces']) || !is_array($ini['interfaces'])) {
                print "No interface defined in $inifile\n";
            } else {
                if (!empty($ini['interfaces'][0])) {
                    $interfaces = array_merge($interfaces, $ini['interfaces']);
                }
            }
        }

        $interfaces = array_map(function ($interface) { return '\\'.strtolower($interface); }, $interfaces);
        
        $this->analyzerIs("Analyzer\\Interfaces\\InterfaceUsage")
             ->fullnspath($interfaces);
        $this->prepareQuery();
    }
}

?>
