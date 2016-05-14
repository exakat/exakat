<?php

!empty($_SERVER['different case']) ? $_SERVER['http_proxy'] : $_SERVER['HTTP_PROXY'];
!empty($_SERVER['same case']) ? $_SERVER['HTTP_PROXY'] : $_SERVER['HTTP_PROXY'];
