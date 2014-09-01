<?php

namespace A;

use OriginalUsed as AliasUnused, OriginalUnused as AliasUsed, OriginalUsed, OriginalUnused, OriginalBothUsed as AliasBothUsed, OriginalBothUnused as AliasBothUnused;
use OriginalUsedSingleAlias as SingleAliasUnused;
use OriginalUnusedSingleAlias as SingleAliasUsed;
use OriginalBothUsedSingleAlias as SingleAliasBothUsed;
use OriginalBothUnusedSingleAlias as SingleAliasBothUnused;
use SingleWithoutAliasUsed;
use SingleWithoutAliasUnused;


$x = new OriginalUsed;
$x1 = new AliasUsed;
$x2 = new OriginalUsed;
$x3 = new OriginalBothUsed;
$x4 = new AliasBothUsed;
$x5 = new OriginalUsedSingleAlias;
$x6 = new SingleAliasUsed;
$x7 = new OriginalBothUsedSingleAlias;
$x8 = new SingleAliasBothUsed;
$x9 = new SingleWithoutAliasUsed;

?>