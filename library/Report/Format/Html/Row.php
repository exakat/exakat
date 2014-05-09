<?php

namespace Report\Format\Html;

class Row extends \Report\Format\Html {
    private $span = 6;
    
    public function render($output, $data) {
        list($left, $right) = $data;
        
        $nspan = 12 - $this->span;
        $html = <<<HTML
							<div>
								<div>
HTML;

        $output->push($html);
        
        if (is_object($left)) {
            $left->render($output);
        }
        
        $html = <<<HTML
								</div>
								<div>

HTML;

        $output->push($html);

        if (is_object($right)) {
            $right->render($output);
        }
        
        $html = <<<HTML
								</div>
							</div>
							<div></div>
HTML;
        $output->push($html);
    }
    
    public function setSpan($span = 6) {
        $this->span = $span;
    }
}

?>