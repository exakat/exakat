<?php
// Create and start timer firing after 2 seconds
$w1 = new EvTimer(2, 0, function () {
    echo "2 seconds elapsed\n";
});

// Create and launch timer firing after 2 seconds repeating each second
// until we manually stop it
$w2 = new EvTimer(2, 1, function ($w) {
    echo "is called every second, is launched after 2 seconds\n";
    echo "iteration = ", Ev::iteration(), PHP_EOL;

    // Stop the watcher after 5 iterations
    Ev::iteration() == 5 and $w->stop();
    // Stop the watcher if further calls cause more than 10 iterations
    Ev::iteration() >= 10 and $w->stop();
});

// Create stopped timer. It will be inactive until we start it ourselves
$w_stopped = EvTimer::createStopped(10, 5, function($w) {
    echo "Callback of a timer created as stopped\n";

    // Stop the watcher after 2 iterations
    Ev::iteration() >= 2 and $w->stop();
});

// Loop until Ev::stop() is called or all of watchers stop
Ev::run();

// Start and look if it works
$w_stopped->start();
echo "Run single iteration\n";
Ev::run(Ev::RUN_ONCE);

echo "Restart the second watcher and try to handle the same events, but don't block\n";
$w2->again();
Ev::run(Ev::RUN_NOWAIT);

$w = new EvTimer(10, 0, function() {});
echo "Running a blocking loop\n";
Ev::run();
echo "END\n";
?>