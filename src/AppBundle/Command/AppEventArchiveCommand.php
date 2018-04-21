<?php

namespace AppBundle\Command;

use AppBundle\Model\EventManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppEventArchiveCommand extends AppCommand
{
    private $commandName = 'app:event:archive';

    /** @var EventManager */
    private $eventManager;

    /**
     * AppEventArchiveCommand constructor.
     *
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        parent::__construct($this->commandName);

        $this->eventManager = $eventManager;
    }


    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription('Archive event and its medias.')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argument = $input->getArgument('argument');

        if ($input->getOption('option')) {
            // ...
        }

        $output->writeln('Command result.');
    }

}
