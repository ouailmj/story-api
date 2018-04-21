<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 20/04/2018
 * Time: 21:15
 */

namespace AppBundle\Scheduling\Task;


use MIT\Bundle\SchedulerBundle\Task\Task;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Process\Process;

class CommandTask extends Task
{
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
        $this->before();

        if ($app instanceof Application){
            $this->cwd = realpath($app->getKernel()->getRootDir().'/../');
        }
        $process = new Process($this->buildCommand(), $this->cwd());
        $process->run();

        $this->after();
        return $process->getOutput();
    }
}