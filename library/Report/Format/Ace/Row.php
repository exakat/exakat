<?php

namespace Report\Format\Ace;

class Row extends \Report\Format\Ace {
    private $span = 6;
    
    public function render($output, $data) {
        $left = $data['left'];
        $right = $data['right'];
        
        $nspan = 12 - $this->span;
        $html = <<<HTML
							<div class="row-fluid">
								<div class="span{$this->span}">
HTML;

        $output->push($html);
        
        if (is_object($left)) {
            $left->render($output);
        }
        
        $html = <<<HTML
								</div>
								<div class="vspace"></div>
								<div class="span{$nspan}">

HTML;

        $output->push($html);

        if (is_object($right)) {
            $right->render($output);
        }
        
        $html = <<<HTML
								</div>
							</div>
							<div class="space-6"></div>
HTML;
        $output->push($html);
    }
    
    public function setSpan($span = 6) {
        $this->span = $span;
    }
}

?>