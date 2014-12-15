<?php

namespace Report\Format\Html;

class Infobox extends \Report\Format\Html { 
    public function render($output, $data) {
        
            $text = <<<HTML
								<div>

HTML;
        
        foreach($data as $row) {
            $text .= <<<HTML
									<div>
										{$row['number']} {$row['content']}
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
