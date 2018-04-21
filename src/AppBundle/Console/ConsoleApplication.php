<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 20/04/2018
 * Time: 20:42
 */

namespace AppBundle\Console;

use AppBundle\Scheduling\Task\CommandTask;
use MIT\Bundle\SchedulerBundle\Console\WithScheduler;
use MIT\Bundle\SchedulerBundle\Task\Task;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class ConsoleApplication extends Application
{
    use WithScheduler;

    public function initSchedule()
    {
        return $this->scheduler->bulk($this->loadTasks());
    }

    private function loadTasks()
    {
        return [
            new CommandTask('ls -al', '* * * * * *')
        ];
    }
}