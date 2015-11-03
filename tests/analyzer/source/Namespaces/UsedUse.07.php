<?php

namespace A;

use OriginalUsed as AliasUnused, OriginalUnused as AliasUsed, OriginalUsed, OriginalUnused, OriginalBothUsed as AliasBothUsed, OriginalBothUnused as AliasBothUnused;
use OriginalUsedSingleAlias as SingleAliasUnused;
use OriginalUnusedSingleAlias as SingleAliasUsed;
use OriginalBothUsedSingleAlias as SingleAliasBothUsed;
use OriginalBothUnusedSingleAlias as SingleAliasBothUnused;
use SingleWithoutAliasUsed;
use SingleWithoutAliasUnused;


OriginalUsed::A;
AliasUsed::A;
OriginalUsed::A;
OriginalBothUsed::A;
AliasBothUsed::A;
OriginalUsedSingleAlias::A;
SingleAliasUsed::A;
OriginalBothUsedSingleAlias::A;
SingleAliasBothUsed::A;
SingleWithoutAliasUsed::A;

?>