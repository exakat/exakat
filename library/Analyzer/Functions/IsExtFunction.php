<?php

namespace Analyzer\Functions;

use Analyzer;

class IsExtFunction extends Analyzer\Analyzer {

    public function analyze() {
        $exts = glob('library/Analyzer/Extensions/*.php');
        $exts[] = 'php_functions.ini';
        
        $functions = array();
        foreach($exts as $ext) {
            $inifile = str_replace('library/Analyzer/Extensions/Ext', '', str_replace('.php', '.ini', $ext));
            if ($inifile == 'library/Analyzer/Extensions/Used.ini') { continue; }
            $ini = $this->loadIni($inifile);
            
            if (!isset($ini['functions']) || !is_array($ini['functions'])) {
                print "No functions defined in $inifile\n";
            } else {
                if (!empty($ini['functions'][0])) {
                    $functions = array_merge($functions, $ini['functions']);
                }
            }
        }
        
        $functions = array_keys(array_count_values($functions));
        $functions = array_map(function ($a) { return '\\'.strtolower($a); }, $functions);
        
        $this->atomIs('Functioncall')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->hasNoIn('METHOD')
             ->fullnspath($functions, true);
        $this->prepareQuery();
    }
}

?>
