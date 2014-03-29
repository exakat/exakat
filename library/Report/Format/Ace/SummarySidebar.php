<?php

namespace Report\Format\Ace;

class SummarySidebar extends \Report\Format\Ace { 
    public function render($output, $data) {
        $text = $this->render2($data);

        $text = <<<TEXT
$text

TEXT;

        $output->push($text);
    }

    private function render2($data) {
        $text = '';
        foreach($data as $row) {
            if (get_class($row) != "Report\\Template\\Section") { continue; }
            $text .= <<<HTML
					<li>
						<a href="{$row->getId()}.html">
							<i class="icon-dashboard"></i>
							<span class="menu-text"> {$row->getName()} </span>
						</a>
					</li>
HTML;
        }
        
        return '				<ul class="nav nav-list">
'.$text.'</ul>'; 
    }
}

?>