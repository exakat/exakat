<?php

namespace Analyzer\Classes;

use Analyzer;

class IsExtClass extends Analyzer\Analyzer {

    public function dependsOn() {
        return array("Analyzer\\Classes\\ClassUsage");
    }
    
    public function analyze() {
        $exts = glob('library/Analyzer/Extensions/*.php');
        $exts[] = 'php_classes.ini';
        
        $classes = array();
        foreach($exts as $ext) {
            $inifile = str_replace('library/Analyzer/Extensions/Ext', '', str_replace('.php', '.ini', $ext));
            if ($inifile == 'library/Analyzer/Extensions/Used.ini') { continue; }
            $ini = $this->loadIni($inifile);
            
            if (!isset($ini['classes']) || !is_array($ini['classes'])) {
                print "No classes defined in $inifile\n";
            } else {
                if (!empty($ini['classes'][0])) {
                    $classes = array_merge($classes, $ini['classes']);
                }
            }
        }
        
        $classes = $this->makeFullNsPath($classes);
        
        $this->analyzerIs("Analyzer\\Classes\\ClassUsage")
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR', 'T_AS'))
             ->fullnspath($classes);
        $this->prepareQuery();
    }
}

?>
