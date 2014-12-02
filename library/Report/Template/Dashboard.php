<?php

namespace Report\Template;

class Dashboard  extends \Report\Template {

    public function render($output) {
        $data = $this->data->getArray();

        $row = new \Report\Template\Row();
        $row->setCss($this->css);

        $relay = array();

        $left = new \Report\Template\Camembert();
        $left->setContent($data['upLeft']);
        $left->setCss($this->css);
        $relay['left'] = $left;

        $right = new \Report\Template\Infobox();
        $right->setContent($data['upRight']);
        $right->setCss($this->css);
        $relay['right'] = $right;

        $row->render($output, $relay);

        // second row

        $row = new \Report\Template\Row();
        $row->setCss($this->css);

        $relay = array();

//        Top 5 errors
        $left = new \Report\Template\Top5();
        $left->setContent($data['downLeft']);
        $left->setCss('top5errors');
        $relay['left'] = $left;

//        Top 5 files
        $right = new \Report\Template\Top5();
        $right->setContent($data['downRight']);
        $right->setCss('top5files');
        $relay['right'] = $right;

        $row->render($output, $relay);
    }
}

?>