<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy Ð Exakat SAS <contact(at)exakat.io>
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


class Stubs extends Reports {
    const FILE_EXTENSION = 'php';
    const FILE_FILENAME  = 'stubs';

    const INDENTATION = '    ';

    public function _generate(array $analyzerList): string {
        $report = new StubsJson();

        $code = json_decode( $report->_generate(array()));

        $result = array();
        foreach($code as $version) {
            foreach($version as $name => $namespace) {
                $result[] = $this->namespace($name, $namespace);
            }
        }

        $return =  "<?php\n" . implode(PHP_EOL, $result) . "\n?>\n";

        print $return;

        return $return;
    }

    private function namespace(string $name, object $namespace): string {
        $result = array('namespace ' . trim($name, '\\') . ' {');

        if (isset($namespace->constants)) {
            foreach($namespace->constants as $constantName => $constant) {
                $result[] = self::INDENTATION . $this->constant($constantName, $constant);
            }
            $result[] = '';
        }

        if (isset($namespace->functions)) {
            foreach($namespace->functions as $functionName => $function) {
                $result[] = self::INDENTATION . $this->function($functionName, $function);
            }
            $result[] = '';
        }

        if (isset($namespace->class)) {
            foreach($namespace->class as $className => $class) {
                $result[] = $this->class($className, $class);
            }
            $result[] = '';
        }

        $result[] = "}\n";

        return join(PHP_EOL, $result);
    }

    private function class(string $name, object $class): string {
        $result = array(self::INDENTATION . "class $name {");

        if (isset($class->constants)) {
            foreach($class->constants as $constantName => $constant) {
                $result[] = self::INDENTATION . self::INDENTATION . $this->constant($constantName, $constant);
            }
        }

        if (isset($class->properties)) {
            foreach($class->properties as $propertyName => $property) {
                $result[] = self::INDENTATION . self::INDENTATION . $this->property($propertyName, $property);
            }
        }

        if (isset($class->methods)) {
            foreach($class->methods as $functionName => $function) {
                $result[] = self::INDENTATION . self::INDENTATION . $this->function($functionName, $function);
            }
        }

        $result[] = self::INDENTATION . "}\n";

        return join(PHP_EOL, $result);
    }

    private function constant(string $name, object $values): string {
        if (isset($values->type) && $values->type == 'define') {
            return "define('$name', $values->value);";
        } else {
            return "$values->visibility const $name = $values->value;";
        }
    }

    private function property(string $name, object $values): string {
        $static   = empty($values->static) ? '' : 'static ';
        $typehint = implode('|', $values->typehint);
        $phpdoc   = empty($values->phpdoc) ? '' : self::INDENTATION . $values->phpdoc . PHP_EOL;
        return $phpdoc . self::INDENTATION . $static . "$values->visibility $name;";
    }

    private function function(string $name, object $values): string {
        $reference  = empty($values->reference) ? '' : '&';
        $visibility = empty($values->visibility) ? '' : $values->visibility . ' ';
        $static     = empty($values->static) ? '' : 'static ';
        $final      = empty($values->final) ? '' : 'static ';
        $typehint   = $values->returntypes[0] === '' ? '' : ': ' . implode('|', $values->returntypes) . ' ';
        $phpdoc     = empty($values->phpdoc) ? '' : self::INDENTATION . $values->phpdoc . PHP_EOL . self::INDENTATION;

        $arguments = array();
        if (isset($values->arguments)) {
            foreach($values->arguments as $argName => $argDetails) {
                $referenceArgs  = empty($values->referenceArgs) ? '' : ' &';
                $typehintArgs   = empty($argDetails->returntypes) ? '' : implode('|', $argDetails->returntypes) . ' ';
                $default        = $argDetails->value === '' ? '' : ' = ' . $argDetails->value;

                $arguments[] = $typehintArgs . $referenceArgs . $argDetails->name . $default;
            }
        }
        $arguments = implode(', ', $arguments);

        return $phpdoc . "{$final}{$visibility}{$static}function {$reference}$name($arguments) $typehint{}";
    }
}

?>