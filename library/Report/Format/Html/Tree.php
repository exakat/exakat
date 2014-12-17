<?php

namespace Report\Format\Html;

class Tree extends \Report\Format\Html { 
    static public $tree_counter = 0;
    
    public function render($output, $data) {
        $html = '<ul>';
        
        foreach($data as $section => $values) {
            $html .= '<li>'.$section."<ul>";
            
            foreach($values as $name => $value) {
                $html .= '<li>'.$name." : $value </li>";
            }
            
            $html .= '</ul></li>';
        }

        $html .= '</ul>';

        $output->push($html);
    }
}

?>
