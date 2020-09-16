<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Tasks\Helpers;

class IsModified extends Plugin {
    public $name = 'isModified';
    public $type = 'boolean';
    private $variables = array('Variable', 'Array', 'Member', 'Staticproperty', 'Phpvariable', );

    public function run(Atom $atom, array $extras): void {
        switch ($atom->atom) {
            case 'Assignation' :
                if (in_array($extras['LEFT']->atom, $this->variables)) {
                    $extras['LEFT']->isModified = true;
                }
                break;

            case 'Cast' :
                if ($atom->token === 'T_UNSET_CAST' &&
                    in_array($extras['CAST']->atom, $this->variables)) {
                    $extras['CAST']->isModified = true;
                }
                break;

            case 'Catch' :
                if (in_array($extras['VARIABLE']->atom, $this->variables)) {
                    $extras['VARIABLE']->isModified = true;
                }
                break;

            case 'Foreach' :
                if (in_array($extras['VALUE']->atom, $this->variables)) {
                    $extras['VALUE']->isModified = true;
                }
                if (isset($extras['INDEX']) && in_array($extras['INDEX']->atom, $this->variables)) {
                    $extras['INDEX']->isModified = true;
                }
                if (!empty(array_filter($extras, function ($x) { return (int) $x->reference; }))) {
                    $extras['SOURCE']->isModified = true;
                }
                break;

            case 'List' :
            case 'Unset' :
                foreach($extras as $extra) {
                    if (in_array($extra->atom, $this->variables)) {
                        $extra->isModified = true;
                    }
                }
                break;

            case 'Parametername' :
                $atom->isModified = true;
                break;

            case 'Arrayappend' :
                if (in_array($extras['APPEND']->atom, $this->variables)) {
                    $extras['APPEND']->isModified = true;
                }
                break;

            case 'Preplusplus' :
                if (in_array($extras['PREPLUSPLUS']->atom, $this->variables)) {
                    $extras['PREPLUSPLUS']->isModified = true;
                }
                break;

            case 'Postplusplus' :
                if (in_array($extras['POSTPLUSPLUS']->atom, $this->variables)) {
                    $extras['POSTPLUSPLUS']->isModified = true;
                }
                break;

            default :
//                print $atom->atom.PHP_EOL;
        }
    }
}

?>
