<?php

namespace Report\Format\Ace;

class Section extends \Report\Format\Ace { 
    public function render($output, $data) {
        // todo link to the actual section  ?

        if ($data->getLevel() == 0) {
            foreach($data->getContent() as $id => $c) {
                $c->render($output);
            }
            $output->toFile2($data->getId().".html", $data);
        } elseif ($data->getLevel() == 1) {
            $output->reset();
            foreach($data->getContent() as $content) {
                $content->render($output);
            }
            $output->toFile2($data->getId().".html", $data);
        } else {
            $output->push("\n								<h{$data->getLevel()} class=\"header smaller lighter blue\">{$data->getName()}</h{$data->getLevel()}>");
        }
    }

}

?>