<?php

namespace component;
class ConsoleController extends \Controller
{

    public function multi_process_work(callable $callback, $task)
    {
        $p = count($task);
        for ($i = 0; $i < $p; $i++) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                exit("pid fork error");
            }
            if ($pid == 0) {
                $callback($task[$i]);
                exit(0);
            }
        }
        while (pcntl_waitpid(0, $status) != -1) {
            $status = pcntl_wexitstatus($status);
        }
    }
}