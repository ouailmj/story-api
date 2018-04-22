<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Developed by MIT <contact@mit-agency.com>
 *
 */

namespace AppBundle\Console;

use AppBundle\Command\AppEventArchiveCommand;
use AppBundle\Command\AppEventCloseCommand;
use AppBundle\Command\AppEventSendChallengesCommand;
use AppBundle\Command\AppEventStartCommand;
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
            new Task('ls -al', '* * * * * *'),

            // We can schedule Symfony commands.
            new CommandTask(AppEventStartCommand::class),
            new CommandTask(AppEventCloseCommand::class),
            new CommandTask(AppEventSendChallengesCommand::class, '* * * * * *'),

            (new CommandTask(AppEventArchiveCommand::class))->hourly(),
        ];
    }
}
