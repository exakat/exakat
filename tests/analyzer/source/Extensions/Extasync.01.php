<?php
namespace Concurrent;
register_shutdown_function(function () {
    echo "===> Shutdown function(s) execute here.\n";
});
$work = function (string $title): void {
    var_dump($title);
};
Task::await(Task::async(function () use ($work) {
    $defer = new Deferred();
    
    Task::await(Task::async($work, 'A'));
    Task::await(Task::async($work, 'B'));
    
    Task::async(function () {
        $defer = new Deferred();
        
        Task::async(function () use ($defer) {
            (new Timer(1000))->awaitTimeout();
            
            $defer->resolve('H :)');
        });
        
        var_dump(Task::await($defer->awaitable()));
    });
    
    Task::async(function () use ($defer) {
        var_dump(Task::await($defer->awaitable()));
    });
    
    $timer = new Timer(500);
    
    Task::async(function () use ($timer, $defer, $work) {
        $timer->awaitTimeout();
        
        $defer->resolve('F');
        
        Task::async($work, 'G');
    });
    
    var_dump('ROOT TASK DONE');
}));
Task::async($work, 'C');
Task::async(function () use ($work) {
    (new Timer(0))->awaitTimeout();
    
    Task::async($work, 'E');
});
Task::async(function ($v) {
    var_dump(Task::await($v));
}, Deferred::value('D'));
var_dump('=> END OF MAIN SCRIPT');

?>