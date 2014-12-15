<?php

namespace Report\Format\Ace;

class Section extends \Report\Format\Ace { 
    public function render($output, $data) {
        // todo link to the actual section  ?

        if ($data->getLevel() == 0) {
            die("Processin level 0 ?? ".__METHOD__);
            
        } elseif ($data->getLevel() == 1) {
            $output->reset();
            foreach($data->getContent() as $content) {
                if (get_class($content) != "Report\\Template\\Section") {
                    $content->render($output);
                }
            }
            $output->toFile2($data->getId().".html", $data);
        } elseif ($data->getLevel() == 2) {
            $output->reset();
            foreach($data->getContent() as $content) {
                if (get_class($content) != "Report\\Template\\Section") {
                    $content->render($output);
                }
            }
            $output->toFile2($data->getId().".html", $data);
        } else {
            $output->push("\n								<h{$data->getLevel()} class=\"header smaller lighter blue\">{$data->getName()}</h{$data->getLevel()}>");
        }
    }

}

?>
