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

namespace AppBundle\Scheduling\Task;

use MIT\Bundle\SchedulerBundle\Task\Task;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;

class CommandTask extends Task
{
    // TODO: Configure the lock.

    protected $command;

    protected $expression;

    protected $description = '';

    protected $output;

    protected $cwd;

    public function getOutput()
    {
        return 'var/logs/console.log';
    }

    public function run($app)
    {
        if ($app instanceof Application) {
            $this->cwd = realpath($app->getKernel()->getRootDir().'/../');
        }

        if (class_exists($class = $this->command)) {
            return $this->runSymfonyCommand($class, $app);
        }

        $process = new Process($this->buildCommand(), $this->cwd());
        $process->run();

        return $process->getOutput();
    }

    /**
     * @param $class
     * @param $app
     *
     * @throws \Exception
     *
     * @return string
     */
    private function runSymfonyCommand($class, $app)
    {
        $cmd = new $class();

        if ($cmd instanceof Command) {
            $args = [];
            $input = new ArrayInput($args);
            $output = new ConsoleOutput();

            return $cmd->run($input, $output);
        }

        throw new \InvalidArgumentException();
    }

    public function buildCommand()
    {
        return $this->command.' > '.$this->getOutput();
    }
}
