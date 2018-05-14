<?php
error_reporting(0);
error_reporting(-1);
error_reporting(1);

htmlspecialchars($str, ENT_COMPAT | ENT_HTML401, 'UTF-8');
htmlspecialchars($str, ENT_COMPAT | ENT_HTML423, 'UTF-8');
