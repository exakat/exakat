<?php

namespace A;

use OriginalUsed as AliasUnused, OriginalUnused as AliasUsed, OriginalUsed, OriginalUnused, OriginalBothUsed as AliasBothUsed, OriginalBothUnused as AliasBothUnused;
use OriginalUsedSingleAlias as SingleAliasUnused;
use OriginalUnusedSingleAlias as SingleAliasUsed;
use OriginalBothUsedSingleAlias as SingleAliasBothUsed;
use OriginalBothUnusedSingleAlias as SingleAliasBothUnused;
use SingleWithoutAliasUsed;
use SingleWithoutAliasUnused;


$x instanceof OriginalUsed;
$x1 instanceof AliasUsed;
$x2 instanceof OriginalUsed;
$x3 instanceof OriginalBothUsed;
$x4 instanceof AliasBothUsed;
$x5 instanceof OriginalUsedSingleAlias;
$x6 instanceof SingleAliasUsed;
$x7 instanceof OriginalBothUsedSingleAlias;
$x8 instanceof SingleAliasBothUsed;
$x9 instanceof SingleWithoutAliasUsed;

?>