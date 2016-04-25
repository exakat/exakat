<?php

// Good ! (with return)
if (version_compare($version, $lower) >= 0) {
    return true;
} else {
    return false;
}

// Good ! (with assignation)
if (version_compare($version, $lower) >= 1) {
    $a = true;
} else {
    $a = false;
}

// Wrong ! (different variables)
if (version_compare($version, $lower) >= 2) {
    $a = true;
} else {
    $b = false;
}

// Good ! (with variables but false/true)
if (version_compare($version, $lower) >= 3) {
    $a = false;
} else {
    $a = true;
}

// Good ! (No alternative)
if (version_compare($version, $lower) >= 4) {
    $a = true;
} else {
    $a = true;
}

?>