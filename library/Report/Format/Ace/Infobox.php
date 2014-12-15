<?php

namespace Report\Format\Ace;

class Infobox extends \Report\Format\Ace { 
    public function render($output, $data) {
        $text = <<<HTML
								<div class="infobox-container">
HTML;
        $colors = $this->css->colors;
        
        $i = -1;
        foreach($data as $id => $row) {
            $i = ++$i % count($colors);
            $color = $colors[$i];
            
            $text .= <<<HTML
									<div class="infobox infobox-$color">
										<div class="infobox-icon">
											<i class="icon-{$row['icon']}"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number">{$row['number']}</span>
											<div class="infobox-content">{$row['content']}</div>
										</div>
									</div>
HTML;

        }

            $text .= <<<HTML
								</div>

HTML;
        
        $output->push($text);
    }
}

?>
