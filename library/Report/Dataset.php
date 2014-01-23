<?php

namespace Report;

class Dataset {
    protected function escapeForMarkdown($value) {
        if (is_array($value)) {
            foreach($value as $id => $v) {
                $value[$id] = $this->escapeForMarkdown($v);
            }
            return $value;
        }
        
        if ($this->string_width($value) > 100) {
            $value = substr($value, 0, 100).' ...';
        }

//|` ' == $key \|| in_array($key,$' `|1|

        $value = preg_replace('/([^\\\\])\|\|/', '$1\\|\\|', $value);
        $value = preg_replace('/([^\\\\])\|/', '$1\\|', $value);
        $value = preg_replace('/^\|/', '\\|', $value); // first of the string

//        $value = preg_replace('/([^\\\\])\*\*/', '$1\\*\\*', $value);
//        $value = preg_replace('/([^\\\\])\*/', '$1\\*', $value);
//        $value = preg_replace('/^\*/', '\\*', $value);

        if (strpos($value, '`') !== false ) {
            $value = str_replace( "\n", '``<BR />``', $value );
            $value = str_replace( "<BR />``<BR />", '<BR /><BR />', $value );
            $value = "`` $value ``";
        } else {
            $value = str_replace( "\n", '`<BR />`', $value );
            $value = str_replace( "<BR />``<BR />", '<BR /><BR />', $value );
            $value = "` $value `";
        }

        return $value;
    }

    protected function string_width($string) {
        $strings = explode("\n", $string);
        $max = 0;
        foreach($strings as $s) {
            $max = max(strlen($s), $max);
        }
        
        return $max;
    }

    static function array2tree($array, $delimiter = '\\') {
        $r = array();
        
        foreach ($array as $a) {
            $parts = explode($delimiter, $a);
            $current = $r;
            foreach($parts as $p) {
                if (isset($current[$p])) {
                    $current = &$current[$p];
                } else {
                    $current[$p] = array();
                    $current = &$current[$p];
                }
            }
        }

        return $r;
    }
}

?>