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
        
        $value = str_replace( "\n", '<BR />', $value );

//|` ' == $key \|| in_array($key,$' `|1|

        $value = preg_replace('/([^\\\\])\|\|/', '$1\\|\\|', $value);
        $value = preg_replace('/([^\\\\])\|/', '$1\\|', $value);
        $value = preg_replace('/^\|/', '\\|', $value); // first of the string

        $value = preg_replace('/([^\\\\])\*\*/', '$1\\*\\*', $value);
        $value = preg_replace('/([^\\\\])\*/', '$1\\*', $value);
        $value = preg_replace('/^\*/', '\\*', $value);

        if (strlen($value) > 255) {
            $value = substr($value, 0, 250).' ...';
        }
        
        if (strpos($value, '`') !== false ) {
            $value = "`` $value ``";
        } else {
            $value = "` $value `";
        }

        return $value;
    }
}

?>