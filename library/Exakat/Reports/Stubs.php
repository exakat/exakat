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
                $result[] = $this->namespace($name, (object) $namespace);
            }
        }

        $return =  "<?php\n" . implode(PHP_EOL, $result) . "\n?>\n";

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

        if (isset($namespace->interface)) {
            foreach($namespace->interface as $interfaceName => $interface) {
                $result[] = $this->interface($interfaceName, $interface);
            }
            $result[] = '';
        }

        if (isset($namespace->trait)) {
            foreach($namespace->trait as $traitName => $trait) {
                $result[] = $this->trait($traitName, $trait);
            }
            $result[] = '';
        }

        $result[] = "}\n";

        return join(PHP_EOL, $result);
    }

    private function class(string $name, object $class): string {
        $final      = empty($class->final)      ? '' : 'final ';
        $abstract   = empty($class->abstract)   ? '' : 'abstract ';
        $implements = empty($class->implements) ? '' : ' implements '.implode(', ', $class->implements);
        $extends    = empty($class->extends)    ? '' : ' extends '.$class->extends;
        $use        = empty($class->use)        ? '' : PHP_EOL.self::INDENTATION.'use '.implode(', ', $class->use).';'.PHP_EOL;
        $result = array(self::INDENTATION . "{$abstract}{$final}class $name{$extends}{$implements} {".$use);

        if (isset($class->constants)) {
            foreach($class->constants as $constantName => $constant) {
                $result[] = self::INDENTATION . self::INDENTATION . $this->constant($constantName, $constant);
            }
            $result[] = '';
        }

        if (isset($class->properties)) {
            foreach($class->properties as $propertyName => $property) {
                $result[] = self::INDENTATION . self::INDENTATION . $this->property($propertyName, $property);
            }
            $result[] = '';
        }

        if (isset($class->methods)) {
            foreach($class->methods as $functionName => $function) {
                $result[] = self::INDENTATION . self::INDENTATION . $this->function($functionName, $function);
            }
        }

        $result[] = self::INDENTATION . "}\n";

        return join(PHP_EOL, $result);
    }

    private function trait(string $name, object $trait): string {
        $use        = empty($class->use)        ? '' : PHP_EOL.self::INDENTATION.'use '.implode(', ', $class->use).';'.PHP_EOL;
        $result = array(self::INDENTATION . "trait $name {".$use);

        if (isset($trait->properties)) {
            foreach($trait->properties as $propertyName => $property) {
                $result[] = self::INDENTATION . self::INDENTATION . $this->property($propertyName, $property);
            }
        }

        if (isset($trait->methods)) {
            foreach($trait->methods as $functionName => $function) {
                $result[] = self::INDENTATION . self::INDENTATION . $this->function($functionName, $function);
            }
        }

        $result[] = self::INDENTATION . "}\n";

        return join(PHP_EOL, $result);
    }

    private function interface(string $name, object $interface): string {
        $extends    = empty($interface->extends) ? '' : ' extends '.$interface->extends;
        $result = array(self::INDENTATION . "interface $name{$extends} {");

        if (isset($interface->constants)) {
            foreach($interface->constants as $constantName => $constant) {
                $result[] = self::INDENTATION . self::INDENTATION . $this->constant($constantName, $constant);
            }
        }

        if (isset($interface->methods)) {
            foreach($interface->methods as $functionName => $function) {
                $result[] = self::INDENTATION . self::INDENTATION . $this->function($functionName, $function, 'interface');
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
        $static     = empty($values->static) ? '' : 'static ';
        $typehint   = implode('|', $values->typehint);
        $phpdoc     = empty($values->phpdoc) ? '' : self::INDENTATION . $values->phpdoc . PHP_EOL;
        $visibility = ($values->visibility ?: 'public') . ' ';

        return $phpdoc . self::INDENTATION . $static . $visibility. $name. ';';
    }

    private function function(string $name, object $values, $type = 'class'): string {
        $reference  = empty($values->reference) ?  ''   : '&';
        if ($type === 'interface') {
            $visibility = '';
            $abstract   = '';
            $block      = ' ;';
        } else {
            $abstract   = empty($values->abstract)   ?   ''   : 'abstract ';
            $visibility = empty($values->visibility) ?   ''   : $values->visibility . ' ';
            $block      = empty($values->abstract)   ?   '{}' : ' ;';
        }
        $static     = empty($values->static) ?     ''   : 'static ';
        $final      = empty($values->final) ?      ''   : 'final ';
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

        return $phpdoc . "{$final}{$abstract}{$visibility}{$static}function {$reference}$name($arguments) $typehint{$block}";
    }
}

?>