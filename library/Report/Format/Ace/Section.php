<?php

namespace Report\Format\Ace;

class Section extends \Report\Format\Ace { 
    public function render($output, $data) {
        // todo link to the actual section  ?

        if ($data->getLevel() == 0) {
            print "ici";
            foreach($data->getContent() as $id => $c) {
                print "$id)\n";
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
            $output->push("								<h{$data->getLevel()} class=\"header smaller lighter blue\">{$data->getName()}</h{$data->getLevel()}>");
        }
    }

}

?>