<?php
$x = <<<EMAIL
mail@server.org
EMAIL;

$x = <<<'NOEMAIL'
mail AT server.org
NOEMAIL;

$y["othermail"."@this.server.org"] = "no@email";

const C = "othermail";
$y[C."@this.server.org"] = "no@email";

?>