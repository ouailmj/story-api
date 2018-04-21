<?php

namespace AppBundle\Command;

use MIT\Bundle\SchedulerBundle\Scheduler\SchedulerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppSchedulerRunCommand extends AppCommand
{
    protected function configure()
    {
        $this
            ->setName('app:scheduler:run')
            ->setDescription('Run the scheduler')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln((new \DateTime())->format(DATE_RFC850).' : Running scheduled tasks');
        $counter=0;
        foreach ($this->scheduler()->dueTasks() as $task){
            // Application is injected in the task so the task can have access to other Symfony commands, services...

            $output->writeln($task->run($this->getApplication()));
            $counter++;
        }
        $output->writeln((new \DateTime())->format(DATE_RFC850).' : Running scheduled tasks finished. '.$counter.' tasks have been executed.');
    }

    /**
     * Get the scheduler.
     *
     * @return SchedulerInterface
     */
    protected function scheduler()
    {
        return $this->getApplication()->getScheduler();
    }

}
