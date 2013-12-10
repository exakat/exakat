<?php

namespace Report;

class HashTable {
    private $hash = array('Empty' => 'hash');
    
    function setContent($hash = array()) {
        if (!is_null($hash)) {
            $this->hash = $hash; 
        } 
    }

    function toMarkdown() {
        if (count($this->hash) == 0)  {
            $report = "Nothing special to report. ";
        } else {
            $report = "| Libel        | Value          | 
| -------:        | -------:          |\n";
            foreach($this->hash as $key => $value) {
                $key = $this->escapeString($key);
                $report .= "|$key|$value|\n";
            }
        }
        
        $report .= "\n";
        
        return $report;
    }

    function toText() {
        if (count($this->hash) == 0)  {
            $report = "Nothing special to report. ";
        } else {
            $report = 
"+-------------------------------+
| Libel        | Value          | 
+-------------------------------+\n";
            foreach($this->hash as $key => $value) {
                if (strlen($key) > 255) {
                    $key = substr($key, 0, 250).' ...';
                }
                $report .= "|$key|$value|\n";
            }
        }
        
        $report .= "+-------------------------------+\n";
        
        return $report;
    }

    function escapeString($string) {
        $string = htmlentities($string);
        $string = str_replace( "\n", '<BR />', $string );
        $string = str_replace('\\', '\\\\', $string);
        $string = str_replace('|', '\\|', $string);
        if (strlen($string) > 255) {
            $string = substr($string, 0, 250).' ...';
        }
        $string = str_replace("\n", '`<br />\n`', $string);
        
        return $string;
    }
}

?>