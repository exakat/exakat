<?php

namespace {
new AjxpRole();

AjxpRole::constante + 1;

if ($a instanceof AjxpRole) {
    AjxpRole::$yes = AjxpRole::methodCall();
}

}

namespace NS {
new AjxpRole();

AjxpRole::constante + 1;

if ($a instanceof AjxpRole) {
    AjxpRole::$yes = AjxpRole::methodCall();
}

function x (AjxpRole $a) {}

class y extends AjxpRole implements AjxpRole {}

class_alias('AjxpRole', $b);
class_alias('\\NS\\AjxpROLE', $b);

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