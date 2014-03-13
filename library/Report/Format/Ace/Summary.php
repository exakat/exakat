<?php

namespace Report\Format\Ace;

class Summary extends \Report\Format\Ace { 
    public function render($output, $data) {
        $text = $this->render2($data);

        $text = <<<TEXT
$text

TEXT;

//        $output->push("$text\n");
        $output->setSummary($text);
//        die(__METHOD__);
    }

    private function render2($data) {
        $text = '';
        foreach($data as $row) {
            if (get_class($row) != "Report\\Template\\Section") { continue; }
            if ($row->getName() == "Summary") { continue; }
            $text .= <<<HTML
					<li>
						<a href="{$row->getId()}.html">
							<i class="icon-dashboard"></i>
							<span class="menu-text"> {$row->getName()} </span>
						</a>
					</li>
HTML;
//str_repeat("  ", )."+ ".."\n";

//            $text .= $this->render2($row->getContent());
        }
        
        return $text; 
    }
}

?>