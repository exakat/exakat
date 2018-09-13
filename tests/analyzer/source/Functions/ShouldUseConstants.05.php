<?php

htmlspecialchars('a', $v ?: ENT_COMPAT);
htmlspecialchars('a', $v ?: 1 + 2);

htmlspecialchars('a', $v ?: (ENT_COMPAT));
htmlspecialchars('a', $v ?: 1 | 2);


?>