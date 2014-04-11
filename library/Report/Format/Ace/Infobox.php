<?php

namespace Report\Format\Ace;

class Infobox extends \Report\Format\Ace { 
    public function render($output, $data) {
        $text = <<<HTML
						<div class="span12">
								<div class="span7 infobox-container">
HTML;
        $colors = array('green', 'black', 'orange', 'pink', 'light-wood', 'wood', 'blue', 'red', 'grey', 'purple');
        
        foreach($data as $row) {
            $color = $colors[rand(0, count($colors) - 1)];
            
            $text .= <<<HTML
									<div class="infobox infobox-$color  ">
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
						</div>

HTML;
        
        $output->push($text);
    }
}

?>