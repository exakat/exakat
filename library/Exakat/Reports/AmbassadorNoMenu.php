<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Docs;
use Exakat\Data\Methods;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;

class AmbassadorNoMenu extends Ambassador {
    protected function getBasedPage($file) {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/base.html');
            $title = ($file == 'index') ? 'Dashboard' : $file;

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $this->config->project);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($this->config->project{0}));

            $baseHTML = $this->injectBloc($baseHTML, 'SIDEBARMENU', '');
            $baseHTML = preg_replace('$<aside class="main-sidebar">.*?</aside>$is', '', $baseHTML);
            $baseHTML = preg_replace('$<aside class="control-sidebar control-sidebar-dark">.*?</aside>$is', '', $baseHTML);
            $baseHTML = preg_replace('$<header class="main-header">.*?</header>$is', '', $baseHTML);
            $baseHTML = preg_replace('$<footer class="main-footer">.*?</footer>$is', '', $baseHTML);
            $baseHTML = preg_replace('$class="content-wrapper"$is', 'class="content-wrapper" style="margin-left: 0px"', $baseHTML);
        }

        $subPageHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/'.$file.'.html');
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }
}

?>