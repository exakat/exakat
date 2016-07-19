<?php

namespace A;

// 4 used, 2 unused
use a\SingleWithoutAliasUsed;
use b\SingleWithoutAliasUnused;
use c\OriginalUsedAliasUnusedo as OriginalUsedAliasUnuseda;
use d\OriginalUnusedAliasUsedo as OriginalUnusedAliasUseda;
use e\OriginalAliasBothUsedo as OriginalAliasBothUseda;
use f\OriginalAliasBothUnusedo as OriginalAliasBothUnused;

// 4 used, 2 unused
use j\SingleWithoutAliasUsed2,
    k\SingleWithoutAliasUnused2,
    l\OriginalUsedAliasUnusedo2 as OriginalUsedAliasUnuseda2,
    m\OriginalUnusedAliasUsedo2 as OriginalUnusedAliasUseda2,
    n\OriginalAliasBothUsedo2 as OriginalAliasBothUseda2,
    o\OriginalAliasBothUnusedo2 as OriginalAliasBothUnused2;

$x instanceof SingleWithoutAliasUsed;
$x1 instanceof \c\OriginalUsedAliasUnusedo;
$x2 instanceof OriginalUnusedAliasUseda;
$x3 instanceof e\OriginalAliasBothUsedo;
$x4 instanceof OriginalAliasBothUseda;

$x2 instanceof SingleWithoutAliasUsed2;
$x12 instanceof \l\OriginalUsedAliasUnusedo2;
$x22 instanceof OriginalUnusedAliasUseda2;
$x32 instanceof n\OriginalAliasBothUsedo2;
$x42 instanceof OriginalAliasBothUseda2;
?>