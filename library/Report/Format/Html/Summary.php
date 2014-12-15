<?php

namespace Report\Format\Html;

class Summary extends \Report\Format\Html { 
    public function render($output, $data) {
        $text = $this->render2($data);

        $text = <<<TEXT
$text

TEXT;

        $output->push("$text\n");
    }

    private function render2($data) {
        $text = '<ul>';
        foreach($data as $row) {
            if (get_class($row) != "Report\\Template\\Section") { continue; }
            if ($row->getName() == "Summary") { continue; }
            $text .= "<li>".$row->getName()."</li>\n";

            $text .= $this->render2($row->getSections());
        }
        
        $text .= "</ul>";
        
        return $text; 
    }
}

?>
