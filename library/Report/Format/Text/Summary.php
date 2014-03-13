<?php

namespace Report\Format\Text;

class Summary extends \Report\Format\Text { 
    public function render($output, $data) {
        $text = $this->render2($data);

        $text = <<<TEXT
$text

TEXT;

        $output->push("$text\n");
    }

    private function render2($data) {
        $text = '';
        foreach($data as $row) {
            if (get_class($row) != "Report\\Template\\Section") { continue; }
            if ($row->getName() == "Summary") { continue; }
            $text .= str_repeat("  ", $row->getLevel())."+ ".$row->getName()."\n";

            $text .= $this->render2($row->getContent());
        }
        
        return $text; 
    }
}

?>