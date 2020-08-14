<?php
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
declare(strict_types = 1);

namespace Exakat\Analyzer\Complete;


class PropagateConstants extends Complete {
    public function analyze(): void {
        $this->readConstantValue();

        $this->pushConstantValues();
        $count = $this->PropagateConstants();

        $this->setCount($count);
    }

    private function propagateConstants(int $level = 0): int {
        $total = 0;

        //Currently handles + - * / % . << >> ** ()
        //Currently handles intval, boolean, noDelimiter (String)
        //Needs realval, arrayval

        $total += $this->processAddition();
        $total += $this->processConcatenation();
        $total += $this->processSign();
        $total += $this->processPower();
        $total += $this->processComparison();
        $total += $this->processLogical();
        $total += $this->processParenthesis();
        $total += $this->processNot();
        $total += $this->processCoalesce();
        $total += $this->processTernary();
        $total += $this->processBitshift();
        $total += $this->processMultiplication();
        $this->readConstantValue();
        $this->pushConstantValues();

        if ($total > 0 && $level < 15) {
            $total += $this->propagateConstants($level + 1);
        }

        return $total;
    }

    private function readConstantValue() {
            display('propagating Constant value in Const');
            // fix path for constants with Const
            // noDelimiter is set at the same moment as boolean and intval. Any of them is the same
            $this->atomIs(array('Constant', 'Defineconstant'))
             ->outIs('VALUE')
             ->atomIs(array('String', 'Heredoc', 'Integer', 'Null', 'Boolean', 'Float'))
             ->setProperty('propagated', true)
             ->count();
            $res = $this->rawQuery();

            $this->atomIs(array('Constant', 'Defineconstant'))
                 ->outIs('VALUE')
                 ->is('propagated', true)
                 ->savePropertyAs('x')
                 ->back('first')

                 ->outIs('NAME')
                 ->hasNo('propagated')
                 ->raw(<<<'GREMLIN'
 sideEffect{ 
        if ("noDelimiter" in x.keys()) {
            it.get().property("noDelimiter", x.value("noDelimiter").toString()); 
        }
        if ("intval" in x.keys()) {
            it.get().property("intval", x.value("intval")); 
        }
        if ("boolean" in x.keys()) {
            it.get().property("boolean", x.value("boolean")); 
        }
        if ("isNull" in x.keys()) {
            it.get().property("isNull", x.value("isNull")); 
        }
        if ("count" in x.keys()) {
            it.get().property("count", x.value("count")); 
        }
        it.get().property("propagated", true); 
}
GREMLIN
)
                 ->count();
            $res = $this->rawQuery();

            display( $res->toInt() . " constants inited\n");
            return $res->toInt();
        }

    private function pushConstantValues() {
        $this->atomIs(array('Constant', 'Defineconstant'))
             ->outIs('NAME')
             ->is('propagated', true)
             ->savePropertyAs('constante')
             ->back('first')

             ->outIs('DEFINITION')
             ->hasNo('propagated')
             ->raw(<<<'GREMLIN'
sideEffect{ 
        if ("intval" in constante.keys()) {
            it.get().property("intval", constante.value("intval")); 
        }
        if ("boolean" in constante.keys()) {
            it.get().property("boolean", constante.value("boolean")); 
        }
        if ("noDelimiter" in constante.keys()) {
            it.get().property("noDelimiter", constante.value("noDelimiter").toString()); 
        }
        if ("isNull" in constante.keys()) {
            it.get().property("isNull", constante.value("isNull")); 
        }
        it.get().property("propagated", true); 
}
GREMLIN
)
            ->count();
            $res = $this->rawQuery();

            display( $res->toInt() . " constants propagated\n");
            return $res->toInt();
        }

    private function processAddition() {
        display('propagating Constant value in Addition');
        // fix path for constants with Const
        $this->atomIs('Addition')
             ->hasNo('propagated')
             ->initVariable('x', '[ ]')
             // Split LEFT and RIGHT to ensure left is in 0
             ->filter(
                $this->side()
                     ->outIs('LEFT')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
             )
             ->filter(
                $this->side()
                     ->outIs('RIGHT')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
             )

            ->raw(<<<'GREMLIN'
 filter{x.size() == 2; }.
sideEffect{ 
    if (it.get().value("token") == 'T_PLUS') {
      i = x[0] + x[1];
    } else if (it.get().value("token") == 'T_MINUS') {
      i = x[0] - x[1];
    }

    it.get().property("intval", i); 
    it.get().property("boolean", i != 0);
    it.get().property("noDelimiter", i.toString()); 
    it.get().property("propagated", true); 
    
    i = null;
}

GREMLIN
)
             ->count();

        $res = $this->rawQuery();
        display('propagating ' . $res->toInt() . ' Addition with constants');

        return $res->toInt();
    }

    private function processConcatenation() {
        display('propagating Constant value in Concatenations');
        $this->atomIs('Concatenation')
             ->hasNo('propagated')
             ->initVariable('x', '[ ]')
             ->not(
                $this->side()
                     ->outIs('CONCAT')
                     ->hasNo('noDelimiter')
             )
             ->not(
                $this->side()
                     ->outIs('CONCAT')
                     ->atomIs(array('Identifier', 'Nsname'))
                     ->hasNo('propagated')
             )
             ->raw('where( __.out("CONCAT").order().by("rank").sideEffect{ x.add( it.get().value("noDelimiter") ) }.count() )')
             ->raw(<<<'GREMLIN'
sideEffect{ 
    s = x.join("");
    it.get().property("noDelimiter", s);

    // Warning : PHP doesn't handle error that same way
    if (s.isInteger()) {
        it.get().property("intval", s.toInteger());
        it.get().property("boolean", true);
    } else {
        it.get().property("intval", 0);
        it.get().property("boolean", false);
    }
    it.get().property("propagated", true); 
    
    x = null;
}

GREMLIN
)
        ->count();

        $res = $this->rawQuery();
        display('propagating ' . $res->toInt() . ' Concatenation with constants');

        return $res->toInt();
    }

    private function processSign() {
        display('propagating Constant value in Sign');
        $this->atomIs('Sign')
             ->hasNo('propagated')
             ->initVariable('x', '[ ]')
             ->not(
                $this->side()
                     ->outIs('SIGN')
                     ->hasNo('intval')
             )
             ->raw('where( __.out("SIGN").sideEffect{ x = it.get().value("intval") }.count() )')
             ->raw(<<<'GREMLIN'
sideEffect{ 
        if (it.get().value("token") == 'T_PLUS') {
            it.get().property("intval", x); 
            it.get().property("boolean", x != 0);
            it.get().property("noDelimiter", x.toString()); 
        } else if (it.get().value("token") == 'T_MINUS') {
            it.get().property("intval", -1 * x); 
            it.get().property("boolean", x != 0);
            it.get().property("noDelimiter", (-1 * x).toString()); 
        }
        it.get().property("propagated", true); 

        i = null;
}
GREMLIN
)
           ->count();
        $res = $this->rawQuery();

        display('propagating ' . $res->toInt() . ' Signs with constants');
        return $res->toInt();
    }

    private function processPower() {
        display('propagating Constant value in Power');
        // fix path for constants with Const
        $this->atomIs('Power')
             ->hasNo('propagated')
             ->initVariable('x', '[ ]')
             ->not(
                $this->side()
                     ->outIs(array('LEFT', 'RIGHT'))
                     ->hasNo('intval')
             )
             // Split LEFT and RIGHT to ensure left is in 0
             ->filter(
                $this->side()
                     ->outIs('LEFT')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
             )
             ->filter(
                $this->side()
                     ->outIs('RIGHT')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
             )

            ->raw(<<<'GREMLIN'
 filter{ x.size() == 2; }.
sideEffect{ 
    try {
        i = (new BigInteger(x[0])) ** (new BigInteger(x[1]));
    } catch (Exception e) {
        // doesn't handle PHP limits at all
        i = 0;
    }

    it.get().property("intval", i.toLong()); 
    it.get().property("boolean", i.toLong() != 0);
    it.get().property("noDelimiter", i.toString()); 
    it.get().property("propagated", true); 

    i = null;
}

GREMLIN
)
            ->count();

            $res = $this->rawQuery();
            display('propagating ' . $res->toInt() . ' power with constants');

            return $res->toInt();
        }

        private function processComparison() {
            display('propagating Constant value in Comparison');
            // fix path for constants with Const
            $this->atomIs('Comparison')
                 ->hasNo('propagated')
                 ->initVariable('x', '[ ]')
                 ->not(
                    $this->side()
                         ->outIs(array('LEFT', 'RIGHT'))
                         ->hasNo('intval')
                 )
                 // Split LEFT and RIGHT to ensure left is in 0
                 ->filter(
                    $this->side()
                         ->outIs('LEFT')
                         ->has('intval')
                         ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
                 )
                 ->filter(
                    $this->side()
                         ->outIs('RIGHT')
                         ->has('intval')
                         ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
                 )

             ->raw(<<<'GREMLIN'
 filter{x.size() == 2; }.
sideEffect{ 
        if (it.get().value("token") == 'T_GREATER') {
          i = x[0] > x[1];
        } else if (it.get().value("token") == 'T_SMALLER') {
          i = x[0] < x[1];
        } else if (it.get().value("token") == 'T_IS_GREATER_OR_EQUAL') {
          i = x[0] >= x[1];
        } else if (it.get().value("token") == 'T_IS_SMALLER_OR_EQUAL') {
          i = x[0] <= x[1];
        } else if (it.get().value("token") == 'T_IS_EQUAL' ||
                   it.get().value("token") == 'T_IS_IDENTICAL') {
          i = x[0] == x[1];
        } else if (it.get().value("token") == 'T_IS_NOT_EQUAL'||
                   it.get().value("token") == 'T_IS_NOT_IDENTICAL') {
          i = x[0] != x[1];
        } else if (it.get().value("token") == 'T_SPACESHIP') {
          i = x[0] <=> x[1];
        }

    it.get().property("intval", i ? 1 : 0); 
    it.get().property("boolean", i != 0);
    it.get().property("noDelimiter", i.toString()); 
    it.get().property("propagated", true); 

    i = null;
}

GREMLIN
)
            ->count();

            $res = $this->rawQuery();
            display('propagating ' . $res->toInt() . ' comparison with constants');

            return $res->toInt();
        }

    private function processLogical() {
            display('propagating Constant value in Logical');
            // fix path for constants with Const
            $this->atomIs(array('Logical', 'Bitoperation'))
                 ->hasNo('propagated')
                 ->initVariable('x', '[ ]')
                 ->not(
                    $this->side()
                         ->outIs(array('LEFT', 'RIGHT'))
                         ->hasNo('intval')
                 )
                 // Split LEFT and RIGHT to ensure left is in 0
                 ->filter(
                    $this->side()
                         ->outIs('LEFT')
                         ->has('intval')
                         ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
                 )
                 ->filter(
                    $this->side()
                         ->outIs('RIGHT')
                         ->has('intval')
                         ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
                 )

             ->raw(<<<'GREMLIN'
 filter{x.size() == 2; }.
sideEffect{ 
      if (it.get().value("token") == 'T_BOOLEAN_AND' ||
          it.get().value("token") == 'T_LOGICAL_AND') {
        i = (x[0] != 0) && (x[1] != 0);
      } else if (it.get().value("token") == 'T_BOOLEAN_OR' ||
                 it.get().value("token") == 'T_LOGICAL_OR') {
        i = (x[0] != 0) || (x[1] != 0);
      } else if (it.get().value("token") == 'T_LOGICAL_XOR') {
        i = (x[0] != 0) ^ (x[1] != 0);
      } else if (it.get().value("token") == 'T_AND') {
        i = x[0] & x[1];
      } else if (it.get().value("token") == 'T_XOR') {
        i = x[0] ^ x[1];
      } else if (it.get().value("token") == 'T_OR') {
        i = x[0] | x[1];
      } 

    it.get().property("intval", i ? 0 : 1); 
    it.get().property("boolean", i != 0);
    it.get().property("noDelimiter", i.toString()); 
    it.get().property("propagated", true); 

}
GREMLIN
)
            ->count();

            $res = $this->rawQuery();
            display('propagating ' . $res->toInt() . ' comparison with constants');

            return $res->toInt();
        }

    private function processParenthesis() {
        display('propagating Constant value in Parenthesis');
        $this->atomIs('Parenthesis')
             ->hasNo('propagated')
             ->initVariable('x', '[ ]')
             ->not(
                $this->side()
                     ->outIs('CODE')
                     ->hasNo('intval')
             )
             ->raw('where( __.out("CODE").sideEffect{ x = it.get() }.fold() )')
             ->raw(<<<'GREMLIN'
sideEffect{ 
    it.get().property("intval", x.value("intval")); 
    it.get().property("boolean", x.value("boolean"));
    if ("noDelimiter" in x.keys()) {
        // Ternary, Comparison
        it.get().property("noDelimiter", x.value("noDelimiter").toString()); 
    }
    it.get().property("propagated", true); 

    x = null;
}
GREMLIN
)
           ->count();
        $res = $this->rawQuery();

        display('propagating ' . $res->toInt() . ' Signs with constants');
        return $res->toInt();
    }

    private function processNot() {
        display('propagating Constant value in Not');
        $this->atomIs('Not')
             ->hasNo('propagated')
             ->initVariable('x', '[ ]')
             ->not(
                $this->side()
                     ->outIs('NOT')
                     ->hasNo('intval')
             )
             ->raw('where( __.out("NOT").sideEffect{ x = it.get() }.count() )')
             ->raw(<<<'GREMLIN'
sideEffect{ 
    if (it.get().value("token") == 'T_BANG') {
      i = !x.value("intval");
    } else if (it.get().value("token") == 'T_TILDE') { 
      i = ~x.value("intval");
    }

    it.get().property("intval", x.value("intval")); 
    it.get().property("boolean", x.value("boolean"));
    it.get().property("noDelimiter", x.value("noDelimiter").toString()); 
    it.get().property("propagated", true); 

    x = null;
}
GREMLIN
)
           ->count();
        $res = $this->rawQuery();

        display('propagating ' . $res->toInt() . ' Not with constants');
        return $res->toInt();
    }

    private function processCoalesce() {
        display('propagating Constant value in Coalesce');
        $this->atomIs('Coalesce')
             ->hasNo('propagated')
             ->initVariable('x', '[ ]')
             ->not(
                $this->side()
                     ->outIs(array('LEFT', 'RIGHT'))
                     ->hasNo('intval')
             )
             // Split LEFT and RIGHT to ensure left is in 0
             ->filter(
                $this->side()
                     ->outIs('LEFT')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
             )
             ->filter(
                $this->side()
                     ->outIs('RIGHT')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
             )
             ->raw(<<<'GREMLIN'
sideEffect{ 
    if (x[0] == 0) {
      i = x[1];
    } else {
      i = x[0];
    }
    
    it.get().property("intval", i); 
    it.get().property("boolean", i != 0);
    it.get().property("noDelimiter", i.toString()); 
    it.get().property("propagated", true); 

    i = null;
}
GREMLIN
)
           ->count();
        $res = $this->rawQuery();

        display('propagating ' . $res->toInt() . ' Coalesce with constants');
        return $res->toInt();
    }

    private function processTernary() {
        display('propagating Constant value in Ternary');
        $this->atomIs('Ternary')
             ->hasNo('propagated')
             ->initVariable('x', '[ ]')
             ->not(
                $this->side()
                     ->outIs(array('CONDITION', 'THEN', 'ELSE'))
                     ->hasNo('intval')
             )
             // Split CONDITION, THEN and ELSE to ensure order
             ->filter(
                $this->side()
                     ->outIs('CONDITION')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get() ) }.fold()')
             )
             ->filter(
                $this->side()
                     ->outIs('THEN')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get() ) }.fold()')
             )
             ->filter(
                $this->side()
                     ->outIs('ELSE')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get() ) }.fold()')
             )
             ->raw(<<<'GREMLIN'
sideEffect{ 
    if (x[0].value("intval") == 0) {
      if (x[1].label() == 'Void') {
          i = x[0].value("intval");
      } else {
          i = x[1].value("intval");
      }
    } else {
      i = x[2].value("intval");
    }

    it.get().property("boolean", i != 0);
    it.get().property("noDelimiter", i.toString()); 
    it.get().property("intval", i); 
    it.get().property("propagated", true); 

    i = null;
}
GREMLIN
)
           ->count();
        $res = $this->rawQuery();

        display('propagating ' . $res->toInt() . ' Ternary with constants');
        return $res->toInt();
    }

    private function processBitshift() {
        display('propagating Constant value in Bitshift');
        $this->atomIs('Bitshift')
             ->hasNo('propagated')
             ->initVariable('x', '[ ]')
             ->not(
                $this->side()
                     ->outIs(array('LEFT', 'RIGHT'))
                     ->hasNo('intval')
             )
             // Split LEFT and RIGHT to ensure left is in 0
             ->filter(
                $this->side()
                     ->outIs('LEFT')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
             )
             ->filter(
                $this->side()
                     ->outIs('RIGHT')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
             )
             ->raw(<<<'GREMLIN'
sideEffect{ 
    if (it.get().value("token") == 'T_SL') {
      i = x[0] << x[1];
    } else if (it.get().value("token") == 'T_SR') {
      i = x[0] >> x[1];
    }
    
    it.get().property("intval", i); 
    it.get().property("boolean", i != 0);
    it.get().property("noDelimiter", i.toString()); 
    it.get().property("propagated", true); 

    i = null;
}
GREMLIN
)
           ->count();
        $res = $this->rawQuery();

        display('propagating ' . $res->toInt() . ' Bitshift with constants');
        return $res->toInt();
    }

    private function processMultiplication() {
        display('propagating Constant value in Multiplication');
        $this->atomIs('Multiplication')
             ->tokenIs('T_PERCENTAGE')
             ->hasNo('propagated')
             ->initVariable('x', '[ ]')
             ->not(
                $this->side()
                     ->outIs(array('LEFT', 'RIGHT'))
                     ->hasNo('intval')
             )
             // Split LEFT and RIGHT to ensure left is in 0
             ->filter(
                $this->side()
                     ->outIs('LEFT')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
             )
             ->filter(
                $this->side()
                     ->outIs('RIGHT')
                     ->has('intval')
                     ->raw('sideEffect{ x.add( it.get().value("intval") ) }.fold()')
             )
             ->raw(<<<'GREMLIN'
sideEffect{ 
    if (it.get().value("token") == 'T_STAR') {
      i = x[0] * x[1];
    } else if (it.get().value("token") == 'T_SLASH') {
      if (x[1] != 0) {
          i = x[0] / x[1];
          i = i.setScale(0, BigDecimal.ROUND_HALF_DOWN).toInteger();
      } else {
          i = 0;
      }
    } else if (it.get().value("token") == 'T_PERCENTAGE') {
      if (x[1] != 0) {
          i = x[0] % x[1];
      } else {
          i = 0;
      }
    } // Final else is an error!
    
    it.get().property("intval", i); 
    it.get().property("boolean", i != 0);
    it.get().property("noDelimiter", i.toString()); 
    it.get().property("propagated", true); 

    i = null;
}
GREMLIN
)
           ->count();
        $res = $this->rawQuery();

        display('propagating ' . $res->toInt() . ' Multiplication with constants');
        return $res->toInt();
    }
}

?>
