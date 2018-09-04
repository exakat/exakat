<?php

// Good ! (with return)
if (version_compare($version, $lower) >= 0) {
    return true;
} else {
    return function($x) { return $b;};
}

if (version_compare($version, $upper) <= 0) {
    return true;
} else {
    return false;
}
