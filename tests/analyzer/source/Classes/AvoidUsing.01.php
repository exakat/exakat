<?php

namespace {
new AjxpRole();

AjxpRole::constante + 1;

if ($a instanceof AjxpRole) {
    AjxpRole::$yes = AjxpRole::methodCall();
}

class_alias('AjxpRole', $b);
class_alias('\\NS\\AjxpROLE', $b);


}

namespace NS {
new AjxpRole();

AjxpRole::constante + 1;

if ($a instanceof AjxpRole) {
    AjxpRole::$yes = AjxpRole::methodCall();
}

function x (AjxpRole $a) {}

class y extends AjxpRole implements AjxpRole {}

}

namespace NS2 {

use NS\AjxpRole as b;

new AjxpRole();

AjxpRole::constante + 1;

if ($a instanceof AjxpRole) {
    AjxpRole::$yes = AjxpRole::methodCall();
}

function x (AjxpRole $a) {}

class y extends AjxpRole implements AjxpRole {}

}

?>