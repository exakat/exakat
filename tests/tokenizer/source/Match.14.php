<?php

function wrong() {
    throw new Exception();
}

echo match (false) {
    '' => wrong(),
    [] => wrong(),
    0 => wrong(),
    0.0 => wrong(),
    false => "false\n",
};
