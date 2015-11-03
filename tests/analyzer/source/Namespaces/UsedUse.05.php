<?php

namespace A;

// 4 used, 2 unused
use SingleWithoutAliasUsed;
use SingleWithoutAliasUnused;
use OriginalUsedAliasUnusedo as OriginalUsedAliasUnuseda;
use OriginalUnusedAliasUsedo as OriginalUnusedAliasUseda;
use OriginalAliasBothUsedo as OriginalAliasBothUseda;
use OriginalAliasBothUnusedo as OriginalAliasBothUnused;

// 4 used, 2 unused
use SingleWithoutAliasUsed2,
    SingleWithoutAliasUnused2,
    OriginalUsedAliasUnusedo2 as OriginalUsedAliasUnuseda2,
    OriginalUnusedAliasUsedo2 as OriginalUnusedAliasUseda2,
    OriginalAliasBothUsedo2 as OriginalAliasBothUseda2,
    OriginalAliasBothUnusedo2 as OriginalAliasBothUnused2;

$x instanceof SingleWithoutAliasUsed;
$x1 instanceof OriginalUsedAliasUnusedo;
$x2 instanceof OriginalUnusedAliasUseda;
$x3 instanceof OriginalAliasBothUsedo;
$x4 instanceof OriginalAliasBothUseda;

$x2 instanceof SingleWithoutAliasUsed2;
$x12 instanceof OriginalUsedAliasUnusedo2;
$x22 instanceof OriginalUnusedAliasUseda2;
$x32 instanceof OriginalAliasBothUsedo2;
$x42 instanceof OriginalAliasBothUseda2;
?>