<?php

namespace Analyzer\Constants;

use Analyzer;

class IsGlobalConstant extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Constants/ConstantUsage');
    }
    
    public function analyze() {
        $exts = self::$docs->listAllAnalyzer('Extensions');
        $exts[] = 'php_constants';
        
        $constants = array();
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext).'.ini';
            $ini = $this->loadIni($inifile);
            
            if (!empty($ini['constants'][0])) {
                $constants = array_merge($constants, $ini['constants']);
            }
        }
        
        $constantsFullNs = $this->makeFullNsPath($constants);
        $constantsFullNsChunks = array_chunk($constantsFullNs, 500);

        foreach($constantsFullNsChunks as $chunk) {
            $chunk = array_map(function ($x) { return str_replace('\\', '\\\\', $x);}, $chunk);
            $this->analyzerIs('Constants/ConstantUsage')
                 ->analyzerIsNot('self')
                 ->tokenIs('T_STRING')  // No namespace
                 ->regex('fullnspath', '\\\\\\\\.+\\\\\\\\.+')
                 // is the constant defined where it should ?
                 ->filter("g.idx('constants')[['path':it.fullnspath]].any() == false")

                 // is the constant defined in the global
                 ->filter("g.idx('constants')[['path':'\\\\' + it.code.toLowerCase()]].any() ||
                           '\\\\' + it.code.toLowerCase() in ['".join("', '", $chunk)."']")
                 ->back('first');
            $this->prepareQuery();
        }

        $this->analyzerIs('Constants/ConstantUsage')
             ->analyzerIsNot('self')
             ->tokenIs('T_STRING')  // No namespace
             ->regex('fullnspath', '^\\\\\\\\[^\\\\\\\\]+\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
